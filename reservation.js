/*
 * reservation v.6
 * AJAX request using Cross-Origin Resource Sharing (CORS)
 * - run custom form action (explicit)
 * - run wf form action (implicit)
 * - popup loading management
 * build: 12.06.2020 | anthonyslamin.ch
 */
document.addEventListener("DOMContentLoaded", (event) => {
    // options
    const subdomain = "mail",
      domain = "tlh-sierre.ch",
      folder = "tlhsierre",
      file = "confirmation",
      version = 6,
      extention = "php";
  
    const log = console.log,
      form = document.getElementsByClassName("form")[0],
      popupSuccess = document.getElementById("popup-success"),
      messageSucess = document.getElementById("message-success"),
      gifSuccess = document.getElementById("gif-success"),
      gifLoading = document.getElementById("gif-loading"),
      buttonClose = document.getElementById("button-close");
    let message;
  
    // on submit, execute custom action
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      popup();
      createCORSRequest();
      makeCORSRequest();
    }); // end click listener
  
    // 🍠 show popup progress on submit click
    function popup() {
      buttonClose.disabled = true;
      popupSuccess.style.display = "flex";
    }
  
    // 🥬 Create the XHR object
    function createCORSRequest(method, action) {
      let xhr = new XMLHttpRequest();
      if ("withCredentials" in xhr) {
        // XHR for Chrome/Firefox/Opera/Safari
        xhr.open(method, action, true);
      } else if (typeof XDomainRequest != "undefined") {
        // XDomainRequest for IE
        xhr = new XDomainRequest();
        xhr.open(method, action);
      } else {
        // CORS not supported
        xhr = null;
      }
      return xhr;
    } // end createCORSRequest()
  
    // 🥭 Make the actual CORS request
    function makeCORSRequest() {
      const method = "POST",
        action = `https://${subdomain}.${domain}/${folder}/${file}_v${version}.${extention}`,
        formData = new FormData(form);
  
      let xhr = createCORSRequest(method, action);
      if (!xhr) {
        alert("Sorry, CORS not supported.");
        return;
      }
  
      // 🍠 on ready state response ready
      xhr.onreadystatechange = () => {
        let state = xhr.readyState;
        let response = xhr.responseText;
  
        switch (state) {
          case 0:
            log("ready state: 0, UNSENT");
            break;
          case 1:
            log("ready state: 1, OPENED");
            break;
          case 2:
            log("ready state: 2, HEADERS RECEIVED");
            break;
          case 3:
            log("ready state 3, LOADING");
            break;
          case 4:
            log(`ready state 4, DONE, XMLHttpRequest response: ${response}`);
            gifLoading.style.display = "none";
            gifSuccess.style.display = "block";
            buttonClose.style.backgroundColor = "#3bbd64";
            buttonClose.disabled = false;
            buttonClose.textContent = "Fermer";
            messageSucess.textContent = response;
            break;
          default:
            log("ready state: DEFAULT");
            messageSucess.textContent =
              "Oops, status non disponible en ce moment...";
        } // end switch()
      }; // end onreadystatechange
  
      // 🍭 XMLHttpRequest transaction completed successfully
      xhr.onload = () => {
        log("XMLHttpRequest completed successfully");
      };
  
      // 🥒 error response handler
      xhr.onerror = () => {
        let message = "😥 Erreur réseau loggée au niveau back-end";
        log(message);
        alert(message);
        // 💡 log the content of formData ... does not work quite yet
        for (let pair of formData.entries()) {
          log(`${pair[0]}: ${pair[1]}`);
        }
      };
  
      xhr.send(formData);
    } // end makeCORSRequest ()
  }); // end DOM listener