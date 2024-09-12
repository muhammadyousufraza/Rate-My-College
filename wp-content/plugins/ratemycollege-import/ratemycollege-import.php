<?php
/*
	Plugin Name: Ratemycollege Import profiles of coaches
	Plugin URI: http://wordpress.org/
	Description: Ratemycollege Import profiles of coaches
	Text Domain: ratemycollege-ipoc
	Domain Path: /languages
	Author: Tung Nguyen
	Author URI: http://fcwordpress.net/
	License: GPLv2
	Version: 1.0.0
*/
// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );
define('RIPOC_TEXT_DOMAIN', 'ratemycollege-ipoc');
define('RIPOC_FILE', basename(__FILE__));
define('RIPOC_URL', plugin_dir_url(__FILE__));
define('RIPOC_MENU_POSITION', 66);  //previously 30
define('RIPOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define('RIPOC_PLUGIN_VER', '1.0.0' );
class Ratemycollege_Import_Profiles_Coaches{
	function __construct(){
		add_action('admin_menu', array($this, 'render_admin_menu'));
		//add_action('admin_init', array($this, 'test'));
	}
	function test(){
		$upload_dir = wp_upload_dir();
		$target_dir = $upload_dir['basedir']."/";
		var_dump($target_dir);
	}
	function handle_import($path_file){
		//Nhúng file PHPExcel
		require_once RIPOC_PLUGIN_DIR.'includes/phpexcel/Classes/PHPExcel.php';
		//Đường dẫn file
		$file = $path_file;
		//Tiến hành xác thực file
		$objFile = PHPExcel_IOFactory::identify($file);
		$objData = PHPExcel_IOFactory::createReader($objFile);
		
		//Chỉ đọc dữ liệu
		$objData->setReadDataOnly(true);
		
		// Load dữ liệu sang dạng đối tượng
		$objPHPExcel = $objData->load($file);
		
		//Lấy ra số trang sử dụng phương thức getSheetCount();
		// Lấy Ra tên trang sử dụng getSheetNames();
		
		//Chọn trang cần truy xuất
		$sheet = $objPHPExcel->setActiveSheetIndex(0);
		
		//Lấy ra số dòng cuối cùng
		$Totalrow = $sheet->getHighestRow();
		//Lấy ra tên cột cuối cùng
		$LastColumn = $sheet->getHighestColumn();
		//var_dump($LastColumn);
		//Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
		$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
		
		//Tạo mảng chứa dữ liệu
		$data = array();
		
		//Tiến hành lặp qua từng ô dữ liệu
		//----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
		for ($i = 2; $i <= $Totalrow; $i++) {
			//----Lặp cột
			for ($j = 0; $j < $TotalCol; $j++) {
				// Tiến hành lấy giá trị của từng ô đổ vào mảng
				$data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
			}
		}
		echo 'Import Results: </br>';
		if($data): $i=1; foreach($data as $data_item):
			$this->insert_coach($data_item,$i);
			$i++;
		endforeach; endif;
		//Hiển thị mảng dữ liệu
		//echo '<pre>';
		//var_dump($data);
	}
	function insert_coach($data_item,$i){
		$full_name=$data_item[3];
		$full_name2=explode(" ",$full_name);
		$first_name=$full_name2[0];
		$last_name=$full_name2[1];
		$conference=$data_item[0];
		$division=$data_item[1];
		$title=$data_item[4];
		$sports=$data_item[5];
		$_coach_school=$data_item[2];
		$current_user = wp_get_current_user();
		// Create post object
		$my_post = array(
			'post_type' => 'coach',
			'post_title'    => $full_name,
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_author'   => $current_user->ID,
		);
		// Insert the post into the database
		$post_id=wp_insert_post( $my_post );
		if($post_id > 0):
			update_post_meta( $post_id, '_coach_first_name', $first_name);
			update_post_meta( $post_id, '_coach_last_name', $last_name);
			update_post_meta( $post_id, '_coach_conference', $conference);
			update_post_meta( $post_id, '_coach_division', $division);
			update_post_meta( $post_id, '_coach_title', $title);
			update_post_meta( $post_id, '_coach_school', $_coach_school);
			update_post_meta( $post_id, '_coach_sports', $sports);
		endif;
		echo $i.' Conference: '.$conference.' | Division: '.$division.' | Contact Name: '.$full_name.' | School: '.$_coach_school.' | Title: '.$title.' | Sports: '.$sports.'</br>';
	}
	function import(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'):
			$upload_dir = wp_upload_dir();
			$target_dir = $upload_dir['basedir']."/";
			$target_file = $target_dir . basename($_FILES["ripoc_template_file"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			// Allow certain file formats
			if($imageFileType != "xlsx") {
				echo "Sorry, only xlsx files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["ripoc_template_file"]["tmp_name"], $target_file)) {
					$this->handle_import($target_file);
					@unlink($target_file);
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
			
		endif;
		include (RIPOC_PLUGIN_DIR. 'views/pages/import.php');
	}
	function render_admin_menu(){
		$import=add_menu_page('Import profiles of coaches',  __('Import profiles of coaches', RIPOC_TEXT_DOMAIN), 'manage_options', RIPOC_FILE, array($this, 'import'), 'dashicons-format-aside', RIPOC_MENU_POSITION);
		add_action( 'admin_print_styles-' . $import, array( $this, 'load_styles_admin' ) );
		add_action( 'admin_print_scripts-' . $import, array( $this, 'load_scripts_admin' ) );
	}
	function load_styles_admin() {
		if ( ! wp_style_is( 'font-awesome-styles', 'registered' ) ) {
			wp_register_style( 'font-awesome-styles', RIPOC_URL . 'assets/css/font-awesome.min.css', array(), RIPOC_PLUGIN_VER, 'all' );
		}
		wp_register_style( 'bootstrap',RIPOC_URL . 'assets/css/bootstrap.min.css', null, RIPOC_PLUGIN_VER, false );
		wp_register_style( 'sb-admin-2',RIPOC_URL . 'assets/css/sb-admin-2.css', null, RIPOC_PLUGIN_VER, false );
		wp_register_style( 'select2',RIPOC_URL . 'assets/css/select2.min.css', null, RIPOC_PLUGIN_VER, false );
		wp_register_style( 'jquery-ui', RIPOC_URL . 'assets/css/jquery-ui.min.css', array(), RIPOC_PLUGIN_VER, false );
		wp_register_style( 'jquery-ui-timepicker', RIPOC_URL . 'assets/css/jquery.ui.timepicker.css', null, RIPOC_PLUGIN_VER, false );
		wp_register_style( 'ripoc-style', RIPOC_URL . 'assets/css/back-end.css', array( 'font-awesome-styles', 'bootstrap', 'jquery-ui', 'jquery-ui-timepicker','select2','wp-color-picker' ), RIPOC_PLUGIN_VER, false );
		wp_enqueue_style( 'ripoc-style' );
	}
	function load_scripts_admin(){
		wp_enqueue_media();
		wp_register_script( 'tether', RIPOC_URL . 'assets/js/tether.min.js', array(), RIPOC_PLUGIN_VER, false );
		wp_register_script( 'bootstrap', RIPOC_URL . 'assets/js/bootstrap.min.js', array( 'jquery', 'tether' ), RIPOC_PLUGIN_VER, false );
		wp_register_script( 'select2', RIPOC_URL . 'assets/js/select2.min.js', array(), RIPOC_PLUGIN_VER, false );
		wp_register_script( 'jquery-ui-timepicker', RIPOC_URL . 'assets/js/jquery.ui.timepicker.js', array( 'jquery' ), RIPOC_PLUGIN_VER, true );
		
		wp_register_script( 'ripoc-script', RIPOC_URL . 'assets/js/back-end.js', array( 'jquery', 'bootstrap', 'jquery-ui-datepicker', 'jquery-ui-timepicker','select2','wp-color-picker'), RIPOC_PLUGIN_VER, true );
		wp_enqueue_script( 'ripoc-script' );
		wp_localize_script( 'ripoc-script',
			'ripoc_vars',
			apply_filters( 'ripoc_vars', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' )
			) )
		);
	}
}
new Ratemycollege_Import_Profiles_Coaches();