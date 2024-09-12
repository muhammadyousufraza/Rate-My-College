jQuery(document).ready(function () {
  // Handle DataTable for reviews
  const table = jQuery("#reviews-list").DataTable({
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          var column = this;
          if (
            column.index() == 1 ||
            column.index() == 2 ||
            column.index() == 3
          ) {
            var text = jQuery("#reviews-list thead td")
              .eq(column.index())
              .text();
            var select = jQuery(
              '<select><option value="">- Select ' +
                text +
                " -</option></select>"
            )
              .appendTo(jQuery("#sort-dropdowns"))
              .on("change", function () {
                var val = jQuery.fn.dataTable.util.escapeRegex(
                  jQuery(this).val()
                );

                column.search(val ? "^" + val + "$" : "", true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                const cleaned = d.replace(/(<([^>]+)>)/gi, "");
                select.append(
                  '<option value="' + cleaned + '">' + cleaned + "</option>"
                );
              });
          }
        });
    },
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5"],
    order: [[0, "desc"]],
    columnDefs: [{ orderable: false, targets: [4, 5, 6, 7, 8, 9, 10, 11] }],
  });

  const hookDeleteAction = function () {
    const buttons = jQuery("#reviews-list button.delete-review:not(.init)");
    jQuery.each(buttons, function () {
      jQuery(this).addClass("init");
    });

    buttons.click(function (e) {
      e.preventDefault();

      var r = confirm("Are you sure you want to delete this review?");
      var id = jQuery(this).data("review");

      if (r === false) {
        return;
      }

      if (id !== undefined) {
        window.location.href = "admin.php?page=reviews&action=delete&id=" + id;
      }
    });
  };

  // init
  hookDeleteAction();

  // trigger delete action on page change
  table.on("page.dt", function () {
    // Handle delete review button
    setTimeout(hookDeleteAction, 500);
  });

  // trigger delete action on search
  table.on("search.dt", function () {
    // Handle delete review button
    setTimeout(hookDeleteAction, 500);
  });
});
