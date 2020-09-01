/*
 * re-order months wrappers based on current month
 * last build: 23.06.2020 | anthonysalamin.ch
*/
document.addEventListener("DOMContentLoaded", (event) => {
    seekMonth();
});

function seekMonth() {
    const log = console.log;
    let date = new Date(),
        nMonth = date.getMonth(); // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
    // log(nMonth);

    const monthIds = [
        "january", // [0]
        "february", // [1]
        "march", // [2]
        "april", // [3]
        "may", // [4]
        "june", // [5]
        "july", // [6] normally not there
        "august", // [7] normally not there
        "september", // [8]
        "october", // [9]
        "november", // [10]
        "december" // [11]
    ];

    // loops though the 12 months to sort (normally use 10 if july and august are not there)
    for (let i = 0; i < 12; i++) {
        // i + 1 to get rid of the 0 index for the css ordering
        let order = (i + 1).toString(), // returns [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
            currentMonth = document.getElementById(monthIds[nMonth]);

        // checks if month really exists
        if (!currentMonth.querySelector(".monthToCheck")) {
            currentMonth.style.order = (order + 1).toString();
            // log(`${monthIds[nMonth]} nMonth ${nMonth} is empty`);
        } else {
            currentMonth.style.order = order;
            // log(`${monthIds[nMonth]} nMonth ${nMonth} is full`);
        }

        // loop back to start months again when last month is reached (december index of 11) (normally use 9 if july and august are not there)
        if (nMonth == 11) {
            nMonth = -1; // set to -1 as we will increment after via the for loop
        } // end if
        nMonth++;
    } // end for
} // end seekMonth()
