    <?php 
    session_start();

    $_SESSION['username'];
    $_SESSION['password'];
    if (!isset($_SESSION['username'])) {
        header("location: login.php");
    } 
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log History</title>
        <!-- <link rel="stylesheet" href="assets/css/sensorLogs.css"> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>

            .table-container {
                max-height: 535px; /* Adjust the height as needed */
                overflow-y: auto;
                text-align: center; /* Center the content horizontally */
            }

            table {
                width: 120px;
                border-collapse: collapse;
                margin: auto; /* Center the table horizontally within the container */
            }
            
            table, th, td {
                border: 1px solid black;
            }

            th, td {
                padding: 8px;
                text-align: left;
                color: black;
            }

            .table-header-date{
                background-color: #2D3142  !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 
            }

            .table-header-un{
                background-color: #12355B !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 
            }

            .table-header-act{
                background-color: #454545 !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 

            }
            @media(max-width: 600px) {

            .table-header-date{
                width: 210px;
                }

            .table-header-un{
                width: 160px;
                }
            
            .table-header-act{
                width: 300px;
                }

            .table-container {
                max-height: 895px; /* Adjust the height as needed */
            }

            .wrapper i {
                display: none;
            }

            #dateFilterInput{
                margin-top: 8px;
                width: 330px;
            }
        }
            

        </style>
        <script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();   
            });
        </script>
    </head>
    <body>
    <?php require_once 'header.php'; ?>
    <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
        <div class="wrapper text-center text-xl-center" style="width: 100%; max-width: 995px; padding: 11px;">
                <div class="row">
                    <div class="col-md-12"><br>
                        <div class="mt-5 mb-2 justify-content-center clearfix">
                            <h2 class="pull-left" style="color:#2E2E2E; font-weight: bold; padding: 5px; padding-bottom: 1px; text-align: justify;">Activity Logs</h2>
        </div>
        <div class="mt-1 mb-3 clearfix" style="position: relative;">
        <input type="text" id="searchInput" style="width:255px; border-width:thin; border-radius: 5px; padding: 5px 5px 5px 10px;" placeholder="Search for records...">
                            <i class="fas fa-search" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 725px; transform: translateY(-50%); cursor: pointer;"></i>

        <select id="sortSelect" style="width:305px; border-width:thin; border-radius: 5px;padding: 5px 5px 5px 10px;">
            <option style="color:black;" disabled selected>Sort by</option>
            <option value="all">Show All</option>
            <option value="timestamp_asc">Sort by Timestamp (Ascending)</option>
            <option value="timestamp_desc">Sort by Timestamp (Descending)</option>
        </select>
        <input type="date" id="dateFilterInput" placeholder="Select a date" style="background: #f5f7f9; border-width:thin; border-radius: 5px; padding: 5px 5px 5px 10px;">
    <button id="filterByDateButton" style = "background-color: #0294DB; color: white; border: none; border-radius: 5px; padding: 5px 10px 5px 10px;">Filter by Date</button>
    <a href="sample.log" download=""><button class="btn-get-started scrollto"  style = "background-color: #0294DB; color: white; border: none; border-radius: 5px; padding: 5px 10px 5px 10px;">Download</button></a>
        </div>

        <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class='col-md-4 table-header-date'>Date & Time</th>
                    <th class='col-md-4 table-header-un'>Username</th>
                    <th class='col-md-4 table-header-act'>Activity Detail</th>
                </tr>
            </thead>
            <tbody id="logContent" class="table-body">
                <!-- Log rows will be added here dynamically -->
            </tbody>
        </table>
        <div id="noDateRecords" class="alert alert-secondary" style="display: none;">
        <p>No records found for the selected date.</p>
    </div>
    <div id="noRecordsFound" class="alert alert-secondary" style="display: none;">
        <p>No records found.</p>
    </div>
        </div>
        </div>
                </div>        
        </div>
    </section>
        <script>
            const logContentElement = document.getElementById('logContent');

            // Function to fetch and display the log file as a table
            function fetchAndDisplayLog() {
                fetch('sample.log') // Replace with the correct path to your log file
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(logText => {
                        const logLines = logText.split('\n');
                        let logTableHTML = '';
                        for (const line of logLines) {
                            if (line.trim() !== '') {
                                const [date, username, activityDetail] = line.split('\t');
                                logTableHTML += `
                                    <tr>
                                        <td>${date}</td>
                                        <td>${username}</td>
                                        <td>${activityDetail}</td>
                                    </tr>
                                `;
                            }
                        }
                        logContentElement.innerHTML = logTableHTML;
                    })
                    .catch(error => {
                        logContentElement.textContent = 'Error fetching log: ' + error.message;
                    });
            }

            // Call the function initially when the page loads
            fetchAndDisplayLog();

                    // Periodically refresh the log content (e.g., every 2 seconds)
            //         const refreshInterval = 2000; // interval to save changes in table
            // setInterval(fetchAndDisplayLog, refreshInterval);




            // to search data
            $(document).ready(function(){
        // to search record
        // Add an event listener to the search input
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase(); // Get the search value and convert it to lowercase
            $("table tbody tr").filter(function() {
                var text = $(this).text().toLowerCase();
                var rowVisible = text.indexOf(value) > -1; // Check if row text contains the search value
                $(this).toggle(rowVisible); // Show or hide the row based on the search result
                return rowVisible; // Return whether the row is visible
            });

            // Show "No records found" message if no rows are visible
            if ($("table tbody tr:visible").length === 0) {
                $("#noRecordsFound").show();
            } else {
                $("#noRecordsFound").hide();
            }

    });


        // filter out timestamp to asc or desc order
        // Add an event listener to the sort select
        $("#sortSelect").on("change", function() {
            var selectedOption = $(this).val();
            var rows = $("table tbody tr").get();

            if (selectedOption === "all") {
                $("table tbody tr").show(); // Show all rows
                return;
            }

            // Sort the rows based on the selected option
            rows.sort(function(a, b) {
                var aValue, bValue;
                if (selectedOption === "timestamp_asc" || selectedOption === "timestamp_desc") {
                    // Convert timestamp strings to Date objects for sorting
                    aValue = new Date($(a).find("td:eq(3)").text());
                    bValue = new Date($(b).find("td:eq(3)").text());
                } else {
                    // Sort other columns as strings
                    aValue = $(a).find("td:eq(2)").text().toLowerCase();
                    bValue = $(b).find("td:eq(2)").text().toLowerCase();
                }

                if (selectedOption === "timestamp_desc" || selectedOption === "water_sensor_desc") {
                    return aValue < bValue ? 1 : -1;
                } else {
                    return aValue > bValue ? 1 : -1;
                }
            });

            // Rebuild the table with sorted rows
            $("table tbody").empty();
            $.each(rows, function(index, row) {
                $("table tbody").append(row);
            });
        });



    
    
// Function to format the timestamp to "mm-dd-yy" format
function formatTimestamp(timestamp) {
    const dateParts = timestamp.split('-');
    if (dateParts.length === 3) {
        const [year, month, day] = dateParts;
        return `${month}-${day}-${year.substring(2)}`;
    }
    return timestamp; // Return as-is if not in the expected format
}

// filter date function
$("#filterByDateButton").on("click", function () {
    var selectedDate = $("#dateFilterInput").val(); // Get the selected date from the input field

    if (selectedDate.trim() === "") {
        // If the date input is empty, show a message and exit
        alert("Please select a date.");
        return;
    }

    var dateFound = false; // Flag to track if any records were found

    $("table tbody").hide(); // Hide all rows
    $("table tbody").filter(function () {
        // Get the timestamp from the row in "mm-dd-yy" format
        var rowTimestamp = $(this).find("td:eq(0)").text();
        var formattedRowTimestamp = formatTimestamp(rowTimestamp);

        // Show only the rows that match the selected date
        if (formattedRowTimestamp === selectedDate) {
            dateFound = true; // Records found for the selected date
            return true; // Show the row
        } else {
            return false; // Hide the row
        }
    }).show();

    // Display the "No records found" message if no records were found
    if (!dateFound) {
        $("#noDateRecords").show();
    } else {
        $("#noDateRecords").hide();
    }
});


        });

        </script>
    </body>
    </html>
