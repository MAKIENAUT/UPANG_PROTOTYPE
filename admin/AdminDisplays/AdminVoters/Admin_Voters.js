function date_display() {
   const weekday = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
   ];
   const month_name = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
   ];
   const date = new Date();
   let day = weekday[date.getDay()];
   let month = month_name[date.getMonth()];
   document.getElementById("datetime").innerHTML =
      day.toLocaleString() +
      ", " +
      month.toLocaleString() +
      " " +
      date.getDate().toLocaleString();
}

function show(n) {
   if (n === 1) {
      console.log(n);
      document.location.href = "../AdminDashboard/Admin_Dashboard.php"
   }else if (n === 2) {
      console.log(n);
      document.location.href = "../AdminResults/Admin_Results.php"
   }else if (n === 3) {
      console.log(n);
      document.location.href = "../AdminVoters/Admin_Voters.php"
   }else if (n === 4) {
      console.log(n);
      document.location.href = "../AdminCandidates/Admin_Candidates.php"
   }
}

function show_tab_position(n) {
   var button_styles = "3px solid steelblue";
   if (n === 1) {
      console.log(n);
      document.getElementById("dashboard").style.borderLeft = button_styles;
   }
   else if (n === 2) {
      console.log(n);
      document.getElementById("result").style.borderLeft = button_styles;
   }
   else if (n === 3) {
      console.log(n);
      document.getElementById("voters").style.borderLeft = button_styles;
   }
   else if (n === 4) {
      console.log(n);
      document.getElementById("candidates").style.borderLeft = button_styles;
   }
}