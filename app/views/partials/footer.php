</div>

<style>
    #footer {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
        width: 100%;
        bottom: 0;
        padding: 10px 0; /* Adds 10px margin above and below the text */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
	}

    #footer p {
        color: #000; /* Dark text color */
    }

    body.dark-mode #footer {
        background-color: rgba(25, 25, 25, 0.9); /* Semi-transparent background */
    }
    body.dark-mode #footer p {
        color: #fff; /* Light text color */
    }
</style>

<div id="footer">
    <p>Website Last updated 2024-05-29 23:00<br>
    Click Rows to view quote</p>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
