</div>
<style>
    #footer {
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        position: relative;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
        width: 100%;
        bottom: 0;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        margin: 0; /* Ensure no margin is added */
        padding: 10px 0; /* Adds padding to space out the text */
    	}

    #footer p {
        margin: 0; /* Remove default margin */
        color: #000; /* Dark text color */
    }

    body.dark-mode #footer {
        background-color: rgba(25, 25, 25, 0.9); /* Semi-transparent background */
    }

    body.dark-mode #footer p {
        color: #fff; /* Light text color */
    }
    .flex-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
</style>
<div class="flex-container">
<div id="footer">Website Last updated 2024-05-29 23:00<br>
Set Margins to 0</div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
