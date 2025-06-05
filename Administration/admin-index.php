<?php

session_start();
session_regenerate_id(true);

if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] == "Branch Manager") {
  define('749gl8balFjd0pdu90129%12LBUX33', true);
  require "bank_stats.php";
}else {
  header("Location: staff-login.php?message=You are not authorized to access this page.");
  exit;
}

$sum_of_transaction = new TransactionsStats;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Silver Bank</title>
    <link rel="stylesheet" href="style-admin-index.css">
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    
</head>
<body>
    <!--Navigation bar-->
    <div class="topnav">
        <span class="hamburger" onclick="toggleNav()">☰</span>

        <a href="admin-index.php">Home
            <img src="../Assets/home.svg" alt="Search customer">
        </a>

        <a href="add-staff.php">Add a staff
            <img src="../Assets/add-employee-icon.svg" alt="Add staff">
        </a>

        <a href="find-customer.php">Find customer
            <img src="../Assets/find-customer-icon.svg" alt="Search customer">
        </a>

        <a href="inspect-transaction.php">Inspect Transaction
            <img src="../Assets/inspection-icon.svg" alt="Inspect transaction">
        </a>

        <a href="staff-log-out.php"> Log out
            <img src="../Assets/log-out-admin.svg" alt="Log out">
        </a>
    </div>

<!--Stats container-->
<div class="container-stats">

    <div class="customer-box stat-box">
        <img src="../Assets/man-and-woman-user-icon.svg" alt="Customers">
        <h3>Customers: <?= UsersStats::totalUsers() ?></h3>
    </div>

    <div class="transaction-box stat-box">
        <img src="../Assets/total-transactions.svg" alt="Total transactions">
        <h3>Total Transactions: <?= TransactionsStats::totalTransactions() ?></h3>
    </div>

    <div class="net-transaction-box stat-box">
        <img src="../Assets/calculator-money-icon.svg" alt="Net transactions">
        <h3>Net transactions: <?= $sum_of_transaction->sum_transactions_amount()?></h3>
    </div> 
</div>


<!--Charts-->

<div class="charts-container">
<canvas id="transaction-stats" style="width:33.33%;max-width:500px"></canvas>
<canvas id="transaction-sum-stats" style="width:33.33%;max-width:500px"></canvas>
</div>

<div class="charts-container stats-box">
  <canvas id="airtime-stats" style="width:33.33%;max-width:500px"></canvas>
  <canvas id="airtime-sum-stats" style="width:33.33%;max-width:500px"></canvas>
</div>
<div class="charts-container stats-box">
  <canvas id="data-stats" style="width:33.33%;max-width:500px"></canvas>
  <canvas id="data-sum-stats" style="width:33.33%;max-width:500px"></canvas>
</div>

<script src="check-staff-session.js"></script>
<script>

  // User activity tracker to keep session alive only on real activity
  let activityTimeout;
  function sendActivityUpdate() {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "check-staff-session.php?update=1&t=" + new Date().getTime(), true);
      xhr.send();
  }
  function activityDetected() {
      clearTimeout(activityTimeout);
      sendActivityUpdate();
      activityTimeout = setTimeout(() => {}, 60000);
  }
  window.addEventListener('mousemove', activityDetected);
  window.addEventListener('keydown', activityDetected);
  window.addEventListener('click', activityDetected);
  window.addEventListener('touchstart', activityDetected);

//Function to toggle the navigation bar on small screens
function toggleNav() {
  const topnav = document.querySelector('.topnav');
  const hamburger = document.querySelector('.hamburger');
  topnav.classList.toggle('active');
  if (topnav.classList.contains('active')) {
      hamburger.textContent = "✖"; // Change to close icon
  } else {
      hamburger.textContent = "☰"; // Change back to menu icon
  }
}

//Number of transactions pie chart
var xValues = ["Deposit", "Withdraw", "Transfer", "Airtime", "Data"];
var yValues = [<?= htmlspecialchars(DepositStats::deposit_transactions())?>, <?= htmlspecialchars(WithdrawStats::withdraw_transactions())?>, <?= htmlspecialchars(TransferStats::transfer_transactions())?>, <?= htmlspecialchars(AirtimeStats::airtime_transactions())?>, <?= htmlspecialchars(DataStats::data_transactions())?>];
var barColors = [
    "#6B8E23", 
  "#B22222", 
  "#4682B4",
  "#FFD700", 
  "#FF8C00"  
];

new Chart("transaction-stats", {
  type: "pie",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Transactions by volume"
    }
  }
});


//Total amount of Transactios bar chart
var xValues = ["Deposit", "Withdraw", "Transafer", "Airtime", "Data"];
var yValues = [ <?=htmlspecialchars(DepositStats::sum_deposit_amount())?>, 
<?=htmlspecialchars(WithdrawStats::sum_withdraw_amount())?>, 
<?=htmlspecialchars(TransferStats::sum_transfer_amount())?>,
<?=htmlspecialchars(AirtimeStats::sum_airtime_amount())?>, 
<?=htmlspecialchars(DataStats::sum_data_amount())?>];
var barColors = ["green", "red","blue","orange","brown"];

new Chart("transaction-sum-stats", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Transactions by Sum"
    },

    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          callback: function(value, index, values) {
          return value.toLocaleString(); 
          }
        }
      }]
    },

    tooltips: {
      callbacks: {
        label: function(tooltipItem, data) {
          return tooltipItem.yLabel.toLocaleString();
        }
      }
    }
  }
});


//Number of individual airtime purchased by network, dougnut

var xValues = ["Airtel", "MTN", "Glo", "9mobile"];
var yValues = [<?= htmlspecialchars(AirtimeStats::airtime_provider_stats()['airtel_total'])?> , <?= htmlspecialchars(AirtimeStats::airtime_provider_stats()['mtn_total'])?>,
<?= htmlspecialchars(AirtimeStats::airtime_provider_stats()['glo_total'])?>, <?= htmlspecialchars(AirtimeStats::airtime_provider_stats()['9mobile_total'])?>];
var barColors = [
  "red",
  "yellow",
  "green",
  "lime",

];

new Chart("airtime-stats", {
  type: "doughnut",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Airtime purchases by Network Provider"
    },
  }
});


//sum of individual airtime purchased
var xValues = ["Airtel", "MTN", "Glo", "9mobile"];
var yValues = [ <?=htmlspecialchars(AirtimeStats::airtime_provider_stats()['airtel_sum'])?>, 
<?=htmlspecialchars(AirtimeStats::airtime_provider_stats()['mtn_sum'])?>, 
<?=htmlspecialchars(AirtimeStats::airtime_provider_stats()['glo_sum'])?>,
<?=htmlspecialchars(AirtimeStats::airtime_provider_stats()['9mobile_sum'])?>, 
];
var barColors = ["red", "yellow","green","lime"];

new Chart("airtime-sum-stats", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Sum of Airtime purchases by network provider"
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true, 
          callback: function(value, index, values) {
          return value.toLocaleString(); 
          }
        }
      }]
    },

    tooltips: {
      callbacks: {
        label: function(tooltipItem, data) {
          return tooltipItem.yLabel.toLocaleString();
        }
      }
    }
  }
});



//Number of individual data purchased by network

var xValues = ["Airtel", "MTN", "Glo", "9mobile"];
var yValues = [<?= htmlspecialchars(DataStats::data_provider_stats()['airtel_total'])?> , <?= htmlspecialchars(DataStats::data_provider_stats()['mtn_total'])?>,
<?= htmlspecialchars(DataStats::data_provider_stats()['glo_total'])?>, <?= htmlspecialchars(DataStats::data_provider_stats()['9mobile_total'])?>];
var barColors = [
  "red",
  "yellow",
  "green",
  "lime",

];

new Chart("data-stats", {
  type: "doughnut",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Data purchases by Network Provider"
    },
  }
});


//sum of individual data purchased by network

var xValues = ["Airtel", "MTN", "Glo", "9mobile"];
var yValues = [ <?=htmlspecialchars(DataStats::data_provider_stats()['airtel_sum'])?>, 
<?=htmlspecialchars(DataStats::data_provider_stats()['mtn_sum'])?>, 
<?=htmlspecialchars(DataStats::data_provider_stats()['glo_sum'])?>,
<?=htmlspecialchars(DataStats::data_provider_stats()['9mobile_sum'])?>, 
];
var barColors = ["red", "yellow","green","lime"];

new Chart("data-sum-stats", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Sum of Data purchases by Network provider"
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true, 
          callback: function(value, index, values) {
          return value.toLocaleString(); 
          }
        }
      }]
    },

    tooltips: {
      callbacks: {
        label: function(tooltipItem, data) {
          return tooltipItem.yLabel.toLocaleString();
        }
      }
    }
  }
});

</script>
</body>
</html>