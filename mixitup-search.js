/*
 * MixItUp 3 + Webflow CMS
 * Filter (one dimension) + sort + search + hash URL
 * Build: 17.06.2020 | anthonysalamin.ch
 */
// 🌮 on DOM loaded
document.addEventListener("DOMContentLoaded", (event) => {
  // 🥑 manage button state
  filterChecked();
  // 🌽 convert wf text field "category" into CSS comboclass
  categToClass();
  // 🍆 convert wf text field "title" into CSS comboclass
  titleToClass();
  // 🍋 initialize MixItUp 3 (incl. search module + hash URL filtering)
  mixItUp();
});

// 🥑 manage button state
function filterChecked() {
  const controls = document.getElementById("filters"),
    buttons = document.getElementsByClassName("filter");
  Array.from(buttons).forEach((button) => {
    button.addEventListener("click", (event) => {
      // use "currentTarget" to select parent trigger not its children...
      let target = event.currentTarget;
      if (target.classList != "filter is-checked") {
        // button not yet checked, remove "old" class + add new one
        controls
          .querySelector(".filter.is-checked")
          .classList.remove("is-checked");
        target.classList.add("is-checked");
      } else {
        // button already checked
        return;
      } // end if
    }); // end eventlistener
  });
} // end checked()

// 🌽 convert wf text field "category" into CSS comboclass
function categToClass() {
  const mixes = document.getElementsByClassName("mix");
  Array.from(mixes).forEach((mix) => {
    let categs = mix.querySelectorAll(".categ");
    Array.from(categs).forEach((categ) => {
      let stringCateg = categ.textContent,
        classNameCateg = stringCateg.split(" ").join("").trim();
      mix.classList.add(classNameCateg.toLowerCase());
    });
  });
}

// 🍆 convert wf text field "title" into CSS comboclass
function titleToClass() {
  const mixes = document.getElementsByClassName("mix");
  Array.from(mixes).forEach((mix) => {
    let stringTitle = mix.querySelector(".title").textContent,
      classNameTitle = stringTitle.split(" ").join("");
    mix.classList.add(classNameTitle.toLowerCase().trim());
  });
}

// 🍋 initialize MixItUp 3 (with search module)
function mixItUp() {
  const container = document.getElementById("container"),
    inputSearch = document.getElementById("input");
  let keyupTimeout,
    searchValue,
    status = document.getElementById("status");
  status.textContent = "Recherche opérationelle.";

  // 🥕 handle button state onMixFail callback
  function mixFailBtn() {
    const controls = document.getElementById("filters"),
      feat = document.getElementById("highlight");
    if (feat.classList != "filter is-checked") {
      // button not yet checked, remove "old" class + add new one
      controls
        .querySelector(".filter.is-checked")
        .classList.remove("is-checked");
      feat.classList.add("is-checked");
    } // end if
  } // end mixFail()

  // mixer options
  let mixer = mixitup(container, {
    load: {
      filter: ".selection"
    },
    animation: {
      duration: 450,
      nudge: true,
      reverseOut: true,
      effects: "fade scale(0.77) translateZ(-68px) stagger(6ms)"
    },
    callbacks: {
      onMixClick: function () {
        // reset the search if a filter is clicked
        if (this.matches("[data-filter]")) {
          inputSearch.value = "";
        } // end if
        status.textContent = "Minute, papillon 🦋";
      }, // end onMixClick
      onMixFail: function () {
        status.textContent = "Ooops 😰 aucun événement trouvé.";
        console.log("Oops 😈 no item match any search filter");
        // reset filter + search to initial state
        setTimeout(function () {
          mixer.filter(".selection");
          mixFailBtn();
          inputSearch.value = "";
        }, 700);
      }, // end onMixFail
      onMixEnd: function () {
        status.textContent = "Recherche terminée.";
      } // end onMixEnd
    } // end callbacks
  });

  // 🍧 handle hash URL filtering
  (function setHashFromFilter() {
    let filterValue,
      filterValueCleaned,
      filterFromHash,
      filters = document.getElementsByClassName("filter");

    Array.from(filters).forEach((filter) => {
      filter.addEventListener("click", (event) => {
        // get the "data-filter" attribute
        if (event.currentTarget.hasAttribute("data-filter")) {
          filterValue = event.currentTarget.getAttribute("data-filter");
        }
        // handle the "all" data-filter edge case
        if (!filterValue.includes(".")) {
          filterValue = `.${filterValue}`;
        }
        filterValueCleaned = filterValue.split(".")[1];
        location.hash = "filter=" + encodeURIComponent(filterValueCleaned);
      }); // end listener

      // 🍪 if hash exists
      if (location.hash) {
        // handling of the data-filter="all" misssing the "."
        if (location.hash == "#filter=all") {
          filterFromHash = "all";
        } else {
          filterFromHash = location.hash.replace("#filter=", ".");
        } // end if
        // update mixer on hash exists
        mixer.filter(filterFromHash);
        // handle button state on hash exists
        let oldFilter = document
          .querySelector(".filter.is-checked")
          .classList.remove("is-checked");
        let newFilter = document
          .querySelector(`[data-filter="${filterFromHash}"]`)
          .classList.add("is-checked");
      }
    });
  })(); // end setHashFromFilter IIFE

  // 🥤 set up a handler to listen for "keyup" events
  inputSearch.addEventListener("keyup", (event) => {
    if (inputSearch.value.length < 1) {
      searchValue = "";
    } else {
      searchValue = inputSearch.value.toLowerCase().trim();
    }

    // basic throttling to prevent mixer thrashing
    clearTimeout(keyupTimeout);
    keyupTimeout = setTimeout(function () {
      filterByString(searchValue);
    }, 350);
  });

  // 🍸 update mixitup mixer.filter method
  function filterByString(searchValue) {
    if (searchValue) {
      // use an attribute wildcard selector to check for matches
      mixer.filter(`[class*="${searchValue}"]`);
    } else {
      // update current category button state
      document
        .querySelector(".filter.is-checked")
        .classList.remove("is-checked");
      document
        .querySelector("[data-filter='.selection']")
        .classList.add("is-checked");
      // update mixer's filter
      mixer.filter(".selection");
    }
  }
}
