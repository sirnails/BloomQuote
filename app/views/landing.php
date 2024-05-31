<?php include_once './app/views/partials/navbar.php'; ?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        background: url('landing_bg.jpg') no-repeat center center/cover;
    }
        

    .circle-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh; /* Adjusted height to center the circle vertically */
        position: relative;
        text-align: center;
    }

    .circle {
        background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
        border-radius: 50%;
        width: 300px;
        height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }

    .circle h1 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #333; /* Dark text color */
    }

    .circle p {
        font-size: 16px;
        margin-bottom: 20px;
        color: #666; /* Lighter text color */
    }

    .circle a {
        margin: 5px;
        padding: 10px 20px;
        text-decoration: none;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .circle a.btn-primary {
        background-color: #007bff;
    }

    .circle a.btn-primary:hover {
        background-color: #0056b3;
    }

    .circle a.btn-secondary {
        background-color: #6c757d;
    }

    .circle a.btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
<div class="circle-container">
    <div class="circle">
        <h1>Welcome <br>to BloomQuote!</h1>
        <p>Your one-stop solution for managing floral event quotes and details.</p>
        <a href="index.php?action=login" class="btn btn-primary">Login</a>
        <a href="index.php?action=register" class="btn btn-secondary">Register</a>
    </div>
</div>
<?php include_once './app/views/partials/footer.php'; ?>