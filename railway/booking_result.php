<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Find Train</title>
  <link rel="icon" type="image/x-icon" href="images/IRCTC1.jpg">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="new.css">
<style type="text/css">
    #myInput {
      border-radius: 20px;
      color: black;
      font-size: 15px;
      padding: 10px 5px 10px 15px;
      margin-bottom: 10px;
  }
</style>
<script type="text/javascript">
      $(document).ready(function() 
      {
        $("#show_hide_password a").on('click', function(event)
        {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text")
            {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-lock text-dark" );
                $('#show_hide_password i').removeClass( "fa-unlock text-dark" );
            }
            else if($('#show_hide_password input').attr("type") == "password")
            {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-lock text-dark" );
                $('#show_hide_password i').addClass( "fa-unlock text-dark" );
            }
        });
    });
  </script>
  <script>
    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>

</head>
<body >
  <?php 
    session_start();
    require "connect_database.php";
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }
    $doj=$_POST["doj"];
    $_SESSION["doj"] = "$doj";
    $sp=$_POST["sp"];
    $_SESSION["sp"] = "$sp";
    $dp=$_POST["dp"];
    $_SESSION["dp"] = "$dp";
    if($sp == $dp)
    {
      echo "
              <div class=\"alert alert-sm alert-danger alert-dismissible\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;
                  </button>
                  <strong>Start Point and Destination Point can not be same</strong> <i class=\"fa fa-frown-o\"></i>.
              </div>
              <div class=\"text-center\">
                  <a  style=\"margin-right: 3%;\" href=\"http://localhost/railway/book_ticket.php\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-arrow-left\"></i> Back </button>
                  </a>
                  <a style=\"margin-left: 3%;\" href=\"http://localhost/railway/index.html\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-home\"></i> Home Page </button>
                  </a>
              </div>
            ";
          $conn->close();
          die();
    }
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $time=date('h:i:s');
    $diff1 = abs(strtotime($doj)-strtotime(date("Y-m-d")));
    $days = floor($diff1 / (60*60*24));
    if($days!=0)
    {
      $query = mysqli_query($conn,"SELECT t.train_number,t.train_name,c.start_point,s1.departure_time,c.destination_point,s2.arrival_time,t.arrival_day,c.class_name,c.ticket_fare1,c.available_seats,c.journey_date FROM train_details as t,tatkal_seats as c, train_schedule as s1,train_schedule as s2 where s1.train_number=t.train_number AND s2.train_number=t.train_number AND s1.station_name='".$sp."' AND s2.station_name='".$dp."' AND t.train_number=c.train_number AND c.start_point='".$sp."' AND c.destination_point='".$dp."' AND c.journey_date='".$doj."' ");
    }
    else
    {
        $query = mysqli_query($conn,"SELECT t.train_number,t.train_name,c.start_point,s1.departure_time,c.destination_point,s2.arrival_time,t.arrival_day,c.class_name,c.ticket_fare1,c.available_seats,c.journey_date FROM train_details as t,tatkal_seats as c, train_schedule as s1,train_schedule as s2 where t.start_time>=CURRENT_TIME() AND s1.train_number=t.train_number AND s2.train_number=t.train_number AND s1.station_name='".$sp."' AND s2.station_name='".$dp."' AND t.train_number=c.train_number AND c.start_point='".$sp."' AND c.destination_point='".$dp."' AND c.journey_date='".$doj."' ");
    }
    if(mysqli_num_rows($query) == 0)
    {
        echo "
              <div class=\"alert alert-sm alert-danger alert-dismissible\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;
                  </button>
                  <strong>No train exist at moment</strong> <i class=\"fa fa-frown-o\"></i>.
              </div>
              <div class=\"text-center\">
                  <a  style=\"margin-right: 3%;\" href=\"http://localhost/railway/book_ticket.php\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-arrow-left\"></i> Back </button>
                  </a>
                  <a style=\"margin-left: 3%;\" href=\"http://localhost/railway/index.html\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-home\"></i> Home Page </button>
                  </a>
              </div>
            ";
          $conn->close();
          die();
    }
    else
    {
      //echo"Hello World";
      echo "
            <div class=\"container-sm\">
             <div class=\"table table-responsive\">
                <div class=\"table-wrapper\">
                  <div class=\"table-title\">
                    <div class=\"row\">
                      <div class=\"col-xs-6\">
                        <h2>Available Trains</h2>
                      </div>
                      <div class=\"col-xs-6\">
                        <input type=\"text\"  id=\"myInput\" style=\"width:60%;color: black;\" onkeyup=\"myFunction()\" placeholder=\"Search using Train Number, Train Name\">
                      </div>
                  </div>
                </div>
                <table class=\"table table-striped table-hover text-center table-bordered\" id=\"myTable\">
                  <thead>
                    <tr>
                      <th class=\"text-center\">Train Number</th>
                      <th class=\"text-center\">Train Name</th>
                      <th class=\"text-center\">Journey Date</th>
                      <th class=\"text-center\">Start Point</th>
                      <th class=\"text-center\">Arrival Time</th>
                      <th class=\"text-center\">Destination Point</th>
                      <th class=\"text-center\">Departure Time</th>
                      <th class=\"text-center\">Arrival Day</th>
                      <th class=\"text-center\">Ticket Class</th>
                      <th class=\"text-center\">Ticket Fare (Per Saet)</th>
                      <th class=\"text-center\">Available Seats</th>
                    </tr>
                  </thead>
                  <tbody>
      ";
                    while($row = mysqli_fetch_array($query))
                    {
                        echo "
                            <tr class=\"text-center\">
                              <td>".$row[0]."</td>
                              <td>".$row[1]."</td>
                              <td>".$row[10]."</td>
                              <td>".$row[2]."</td>
                              <td>".$row[3]."</td>
                              <td>".$row[4]."</td>
                              <td>".$row[5]."</td>
                              <td>".$row[6]."</td>
                              <td>".$row[7]."</td>
                              <td>".$row[8]."</td>
                              <td>".$row[9]."</td>
                            </tr>
                        ";
                      }
      echo"
                  </tbody>
                </table>
              </div>
             </div>        
            </div>
            <div class=\"text-center\">
                  <a  style=\"margin-right: 3%;\" href=\"http://localhost/railway/book_ticket.php\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-arrow-left\"></i> Back </button>
                  </a>
                  <a style=\"margin-left: 3%;\" href=\"http://localhost/railway/index.html\">
                      <button class=\"btn btn-primary\"><i class=\"fa fa-home\"></i> Home Page </button>
                  </a>
            </div>
      ";
    }
  ?>
  <script>
    function myFunction()
    {
      var input, filter, table, tr, i, j, column_length, count_td;
        column_length = document.getElementById('myTable').rows[0].cells.length;
          input = document.getElementById("myInput");
          filter = input.value.toUpperCase();
          table = document.getElementById("myTable");
          tr = table.getElementsByTagName("tr");
          for (i = 1; i < tr.length; i++) 
          {
            count_td = 0;
            for(j = 0; j < 2; j++)
            {
                td = tr[i].getElementsByTagName("td")[j];
                if (td)
                {
                    if ( td.innerHTML.toUpperCase().indexOf(filter) > -1) 
                    {            
                      count_td++;
                    }
                }
            }
            if(count_td > 0)
            {
                tr[i].style.display = "";
            }
            else
            {
                tr[i].style.display = "none";
            }
          }
      }
  </script>
</body>
</html>