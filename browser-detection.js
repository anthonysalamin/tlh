/*
 * serach and destroy internet explorer
*/
function searchAndDestroy() {
    var internetExplorer = navigator.userAgent.match(/Trident.*rv[ :]*11\./i);
    var userAgent = navigator.userAgent.match(
        /(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i
    );
    if (internetExplorer) {
        // shame on you
        alert(
            "Oops, vous utilisez Internet Explorer. Pour que le module de réservation fonctionne de manière optimale, préférez un navigateur sécurisé tel que Chrome, Safari ou Edge."
        );
        setTimeout(function () {
            // redirect to Chrome download page
            window.open("https://www.google.com/chrome/");
        }, 1500);
    } else {
        // good user, rock on
        console.log("Yay 🍑 your modern browser is " + userAgent[1]);
    }
}
searchAndDestroy();