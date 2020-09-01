/*
 * re-order events based on current date
 * last build: 24.06.2020
 */
document.addEventListener("DOMContentLoaded", () => {
    seekEvent();
});

function seekEvent() {
    const log = console.log;
    let date = new Date(),
        day = date.getDate(),
        nMonth = date.getMonth(); // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]

    /*
    if (nMonth != 6 || nMonth != 7) {
      log(`Yay 🌝 TLH is open`);
    } else {
      log(`Oops 🌒 TLH is closed`);
    }
    */

    const monthIds = [
        "january", // [0]
        "february", // [1]
        "march", // [2]
        "april", // [3]
        "may", // [4]
        "june", // [5]
        "juillet", // [6]
        "aout", // [7]
        "september", // [8]
        "october", // [9]
        "november", // [10]
        "december" // [11]
    ];

    // map nMonth variable as the index to select the item in the monthIds array
    let wrapperMonth = document.getElementById(monthIds[nMonth]),
        collection_list = wrapperMonth.querySelector(".collection_list"),
        endDates = collection_list.querySelectorAll(".dateclass.margin-left");

    for (let j = 0; j < endDates.length; j++) {
        let endDate = endDates[j],
            valueDate = Number(endDate.innerHTML),
            parentItem = endDate.parentNode, // add 5x .parentNode for production...
            order = (j + 1).toString();

        if (valueDate < day) {
            parentItem.style.order = (order + 1).toString();
            parentItem.style.opacity = "0.2";
        } else {
            parentItem.style.order = order;
        } // end if
    } // end for
} // end seekMonth()
