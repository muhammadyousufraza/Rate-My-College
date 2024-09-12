// Handle Find a School search
jQuery("#schoolName, #coachName, #sportName, #coachName2").autocomplete({
  source: function (request, response) {
    // find type
    var search_type = jQuery(this.element).attr("id");

    // find by
    if (search_type == "schoolName") {
      var search_by = jQuery('[data-search="schoolMenu"] .active').data(
        "school_search_by"
      );
    }

    if (search_type == "coachName") {
      var search_by = jQuery('[data-search="coachMenu"] .active').data(
        "coach_search_by"
      );
    }

    if (search_type == "sportName") {
      var search_by = jQuery('[data-search="sportMenu"] .active').data(
        "sport_search_by"
      );
    }

    if (search_type == "coachName2") {
      var search_by = jQuery('[data-search="coachMenu2"] .active').data(
        "coach2_search_by"
      );
      var sport = jQuery('[data-search="coachMenu2"]')
        .parent()
        .find("#sportName")
        .val();
    }

    // Send to ajax for results
    var postData = {
      action: "rmc_search",
      search_by: search_by,
      search_type: search_type,
      query: request.term,
    };

    if (sport !== undefined && sport !== "") {
      postData.sport = sport;
    }

    jQuery.post(ratemycollege_vars.ajax_url, postData, function (resp) {
      if (!resp.length) {
        var result = [
          {
            label: "No matches found",
            value: request.term,
          },
        ];
        response(result);
      } else {
        // normal response
        response(resp);
      }
    });
  },
  minLength: 2,
  select: function (event, ui) {
    if (ui.item.permalink !== undefined) {
      window.location.href = ui.item.permalink;
    }
  },
});

// handle podcast embeds
const podcastItems = document.querySelectorAll("ul.podcast-list a");
const playerWrap = document.querySelector("#podcast-embed-wrap");

const loadYTEmbed = function (id) {
  const code =
    '<iframe width="560" height="315" src="https://www.youtube.com/embed/' +
    id +
    '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

  playerWrap.innerHTML = code;
};

const clearActive = function () {
  podcastItems.forEach((el) => {
    el.classList.remove("active");
  });
};

if (podcastItems.length) {
  let cnt = 0;
  podcastItems.forEach((el) => {
    el.addEventListener("click", function (e) {
      clearActive();
      const id = e.target.dataset.id;
      if (id) {
        loadYTEmbed(id);
        e.target.classList.add("active");
      }
    });

    // load the first item by default
    if (cnt === 0) {
      el.click();
      el.classList.add("active");
    }

    cnt++;
  });
}
