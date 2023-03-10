// When the user scrolls the page, execute myFunction
window.onscroll = function () {
  myFunction();
  scrollFunction();
};

// // Check if the page is hidden
// document.addEventListener('visibilitychange', function() {
//   if (document.hidden) {
//     document.body.style.filter = 'blur(5px)'; // Blur the screen
//   } else {
//     document.body.style.filter = 'none'; // Unblur the screen
//   }
// });

// // Check if the page is hidden for more than 1 second, which may indicate a screenshot
// setInterval(function() {
//   if (document.hidden) {
//     setTimeout(function() {
//       if (document.hidden) {
//         if (confirm("We detected that you've changed tabs in your browser. \n We are then obliged to cancel your session \n as per security puposes and prevention of screenshot in ballot proper") == true) {
//           document.location.href = "../../voter_login.php";
//         } else {
//           document.location.href = "../../voter_login.php";
//         }
//       }
//     }, 1000);
//   }
// }, 1000);

function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height =
    document.documentElement.scrollHeight -
    document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";
}

function scrollFunction() {
  if (document.body.scrollTop > 230 || document.documentElement.scrollTop > 230) {
    document.getElementById("progress-container").style.top = "0";
    document.getElementById("progress-container").style.width = "100%";
    document.getElementById("progress-container").style.height = "30px";
    document.getElementById("progress-container").style.display = "flex";
    document.getElementById("progress-container").style.padding = "0 20px";
    document.getElementById("progress-container").style.position = "fixed";
    document.getElementById("progress-container").style.alignItems = "center";
    document.getElementById("progress-container").style.backgroundSize = "cover";
    document.getElementById("progress-container").style.backgroundRepeat = "repeat";
    document.getElementById("progress-container").style.backgroundPosition = "center";
    document.getElementById("progress-container").style.backgroundImage = "url(../../photos/background.jpg)";
  } else {
    document.getElementById("progress-container").style.top = "260px";
    document.getElementById("progress-container").style.width = "90%";    
    document.getElementById("progress-container").style.height = "8px";
    document.getElementById("progress-container").style.display = "flex";
    document.getElementById("progress-container").style.padding = "0";
    document.getElementById("progress-container").style.alignItems = "center";
    document.getElementById("progress-container").style.position = "absolute";
    document.getElementById("progress-container").style.background = "#ccc";
  }
}


