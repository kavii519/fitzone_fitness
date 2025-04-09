<?php
session_start();

$host = 'localhost';
$dbname = 'fitzone_fitness';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Booking Process
if (isset($_POST['book_class'])) {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $class_name = $_POST['class_name'];

    $stmt = $conn->prepare("INSERT INTO bookings (username, class_name, full_name, contact) VALUES (:username, :class_name, :full_name, :contact)");
    $stmt->execute([
        'username' => $_SESSION['username'],
        'class_name' => $class_name,
        'full_name' => $full_name,
        'contact' => $contact
    ]);

    // Check if the booking was successful
    if ($stmt->rowCount() > 0) {
        // Redirect to the same page to avoid resubmission, or load a new page
        echo "<script>alert('Class booked successfully!');</script>";
        // Optional: You can redirect to another page or refresh the current page
        // header("Location: " . $_SERVER['PHP_SELF']); // Uncomment if you want to redirect
        // exit();
    } else {
        echo "<script>alert('Booking failed. Please try again.');</script>";
    }
}

// Check if the membership form was submitted
if (isset($_POST['membership'])) {
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    $membership_option = $_POST['membership_option'];

    // Insert the data into the get_membership table
    $stmt = $conn->prepare("INSERT INTO get_membership (username, contact, membership_option) VALUES (:username, :contact, :membership_option)");
    $stmt->execute([
        'username' => $username,
        'contact' => $contact,
        'membership_option' => $membership_option
    ]);

    // Set a success message
    echo "<script>alert('Congratulations! Your membership got successfully.');</script>";
}


// Logout Process
if (isset($_GET['logout'])) {
    // Destroy the session to log out the user
    session_destroy();
    
    // Redirect to the current page (or any other page)
    header("Location: " . $_SERVER['PHP_SELF'] . "?logout_success=1");
    exit();
}

// Check for logout success message and display alert
if (isset($_GET['logout_success']) && $_GET['logout_success'] == 1) {
    echo "<script>alert('Logout successfully!');</script>";
}

// Query Submission

if (isset($_POST['submit_query'])) {
    // Check if the keys exist in $_POST before accessing them
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    // Validate that the required fields are not null
    if ($name && $email && $message) {
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO queries (name, email, message) VALUES (:name, :email, :message)");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message
        ]);

        echo "<script>alert('Your query has been submitted successfully!');</script>";
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FitZone Fitness Center</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body 
        {
            font-family: Times, sans-serif;
            font-size: 24px;
            height: auto;
            display: flex;
            flex-direction: column;
            background: url('my_fitness.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .header 
        {
            display: flex;
            font-size: 36px;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(0, 123, 255, 0.7);
        }
        .pages{
            display: flex;
            font-size: 24px;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgb(57, 47, 47);
        }
        h1 
        {
            margin: 0;
            font-size: 36px;
        }
        nav ul 
        {
            display: flex;
            gap: 15px;
            list-style-type: none;
        }
        nav ul li 
        {
            padding: 10px;
        }
        nav ul li a 
        {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .container 
        {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            margin-top: 20px;
            text size: 24px;
        }
        input, select, button 
        {
            padding: 10px;
            background-color: #fff;
            color: rgb(0,123,255);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button 
        {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover 
        {
            background-color: #0056b3;
        }
        .blog-image 
        {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        } 
        .logo-image
        {
            width: 200px;
            height: 150px;
            margin: 0;
        }
        .welcome-image
        {
            width: 100%; 
            max-width: 500px; 
            margin-top: 10px; 
        }
        .class-image 
        {
            width: 100%; 
            max-width: 500px; 
            margin-top: 10px; 
        }
        .blog-image 
        {
            width: 100%; 
            max-width: 600px; 
            margin-top: 10px; 
        }
        .foloow-image
        {
            width: 100%;
            max-width: 50px;
            margin-top: 10px;
        }
        .questions{
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            text-align: left;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            margin-top: 20px;
            text size: 24px;
        }
        .submit{
            text-align: center;
            background: black;
            margin: 0;
        }
        /* Add this CSS to your stylesheet in FitZone.php */
.search-bar {
    display: flex;
    align-items: center;
    margin-left: 20px;
}

.search-bar input[type="text"] {
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 4px 0 0 4px;
    outline: none;
}

.search-bar button {
    padding: 5px 15px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    transition: background-color 0.3s;
}

.search-bar button:hover {
    background-color: #555;
}

        
    </style>
</head>
<body>

<div class="header">
    <h1>FitZone Fitness Center</h1>
    <img src = "logo.jpg" class = "logo-image">
<form action="search.php" method="GET" class="search-bar">
    <input type="text" name="query" placeholder="Search..." required>
    <button type="submit">Search</button>
</form>
    </div>
    <div class = "pages">
    <nav>
    <ul>
        <li><a href="FitZone.php?page=home">Home</a></li>
        <li><a href="FitZone.php?page=classes">Our Classes</a></li>
        <li><a href="FitZone.php?page=membership">Membership Options</a></li>
        <li><a href="FitZone.php?page=personal_training">Personal Training</a></li>
        <li><a href="FitZone.php?page=about_us">About Us</a></li>
        <?php if (!isset($_SESSION['username'])): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php else: ?>
            <li><a href="FitZone.php?page=get_membership">Get a Membership</a></li>
            <li><a href="FitZone.php?page=book_class">Book a Class</a></li>
            <li><a href="FitZone.php?page=submit_query">FAQ</a></li>
            <li><a href="?logout=true">Logout</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            <?php elseif ($_SESSION['role'] === 'staff'): ?>
                <li><a href="staff_dashboard.php">Staff Dashboard</a></li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</nav>
</div>

<div class="container">
    <?php
    // Page selection logic
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    if ($page == 'home'): ?>
        <h2>Welcome to FitZone</h2>
        <br><br><br>
        <p>Where your health and fitness journey begins! Established with the vision of creating a welcoming and motivating environment, we are dedicated to helping individuals of all fitness levels achieve their goals.
            <br><br>
            At FitZone, we believe that fitness is not just about working out; it’s about building a community. Our state-of-the-art facility features a wide range of equipment, innovative classes, and personalized training programs tailored to meet your unique needs. Whether you’re a beginner looking to start your fitness journey or a seasoned athlete aiming to push your limits, we have something for everyone.
            <br><br>
            Our team of experienced trainers and staff are passionate about fitness and committed to providing exceptional support and guidance. We offer various classes, including yoga, cycling, strength training, and high-intensity interval training (HIIT), ensuring that you can find the perfect fit for your lifestyle.
            <br><br>
            At FitZone, we prioritize a holistic approach to health, emphasizing not only physical fitness but also mental well-being. Join us today and become a part of our vibrant community, where together we can achieve greatness, celebrate successes, and inspire one another every step of the way.
            <br><br>
            <b>Your journey starts here—let’s get fit together!</b>
            <br><br>
            <b style = 'color: red;'> Please be register or login to get a membership / Book a class or write a query </b>
            <br><br>
            <img src = "ft_1.jpg" class = "welcome-image">
            <img src = "ft_2.jpg" class = "welcome-image"><br><br>
            <img src = "ft_3.jpg" class = "welcome-image">
            <img src = "ft_4.jpg" class = "welcome-image">
    </p>
    <br><br><br>

    <?php elseif ($page == 'classes'): ?>
        <h2>Our Classes</h2>
        <br><br>
        <p>We offer classes such as:</p>
        <br><br>
        <ul style="list-style-type: none; padding: 0;">
            <li>
                <strong> 1. Cardio :</strong> <br>
                <p> At FitZone Fitness Center, our Cardio Class is an energizing workout designed to get your heart pumping and boost your fitness levels. This dynamic session combines various cardio exercises, including running, cycling, and high-intensity interval training, to help you burn calories, improve endurance, and enhance overall cardiovascular health. Led by our experienced trainers, the class is suitable for all fitness levels, providing modifications to ensure everyone can participate. Join us for a fun and motivating atmosphere where you can challenge yourself and meet fellow fitness enthusiasts on the journey to better health!</p>
                    <br>
                <img src="cardio-class.jpg" alt="Cardio Class" class="class-image">
            </li>
            <br><br>
            <li>
                <strong> 2. Strength Training :</strong> <br>
                <p> At FitZone Fitness Center, our Strength Training Class is designed to build muscle, enhance strength, and improve overall fitness. This class focuses on a variety of resistance exercises using free weights, machines, and bodyweight movements to target all major muscle groups. Suitable for all fitness levels, our certified trainers provide personalized guidance and support, ensuring you use proper form and technique. Whether you’re a beginner looking to get started or an experienced lifter aiming to push your limits, join us to increase your strength, boost your metabolism, and achieve your fitness goals in a motivating environment!</p>
                <br>
                <img src="strength-training.jpg" alt="Strength Training Class" class="class-image">
            </li>
            <br><br>
            <li>
                <strong> 3. Yoga :</strong> <br>
                <p>At FitZone Fitness Center, our Yoga Class offers a tranquil escape to enhance your physical and mental well-being. This class blends various yoga styles, focusing on flexibility, strength, and mindfulness. Led by our experienced instructors, participants will explore a range of poses and breathing techniques designed to promote relaxation, reduce stress, and improve overall balance. Suitable for all skill levels, our supportive environment encourages everyone to progress at their own pace. Join us on the mat to find your center, cultivate inner peace, and experience the transformative benefits of yoga in a welcoming community!</p>
                <br>
                <img src="yoga-class.jpg" alt="Yoga Class" class="class-image">
            </li>
            <br><br>
            <li>
                <strong> 4. Cycling :</strong><br>
                <p>At FitZone Fitness Center, our Cycling Class is an exhilarating workout that combines high-energy music with a motivating atmosphere to get your heart racing. Participants will engage in a series of challenging rides, including climbs, sprints, and endurance intervals, all led by our enthusiastic instructors. Suitable for all fitness levels, this class focuses on building cardiovascular strength and stamina while toning the legs and core. Whether you’re a seasoned cyclist or a beginner, join us for an empowering experience that will leave you feeling invigorated and accomplished!</p>
                <br>
                <img src = "cycling.jpg" class = "class-image">
            </li>
            <br><br>
            <li>
                <strong> 5. High - Intensity Interval Training (HIIT) : </strong> <br>
                <p>At FitZone Fitness Center, our High-Intensity Interval Training (HIIT) Class is designed to maximize your workout in a short amount of time. This fast-paced session alternates between intense bursts of exercise and short recovery periods, helping you burn calories, build strength, and boost your endurance. Led by our certified trainers, the class incorporates a variety of bodyweight movements, resistance exercises, and cardio intervals to keep your heart rate elevated and your body challenged. Suitable for all fitness levels, HIIT provides an energizing and efficient workout that not only improves your fitness but also leaves you feeling accomplished and ready for more!</p>
                <br>
                <img src = "HIIT.jpg" class = "class-image">
            </li>
            <br><br><br>

        <?php elseif ($page == 'membership'): ?>
            <h2> Membership Option </h2>
            <br><br>
            <p>Join us and choose a membership that fits your lifestyle!</p><br>
        <ul>
            <li><strong>Basic Plan :</strong> LKR 1,600/month - Access to all group classes</li><br>
            <li><strong>Premium Plan :</strong> LKR 2,300/month - Access to all classes + 2 personal training sessions</li><br>
            <li><strong>Family Plan :</strong> LKR 6,000/month - Up to 4 family members</li>
            <br><br><br>
        </ul>

        <?php elseif ($page == 'get_membership'): ?>
        <form method="post">
            <input type="text" name="username" placeholder="Your Name" required><br><br>
            <input type="int" name="contact" placeholder="Contact No" required><br><br>
            <select name="membership_option" required >
                <option value="" disabled selected>Select Option</option>
                <option value="Basic Plan">Basic Plan</option>
                <option value="Premium Plan">Premium Plan</option>
                <option value="Family Plan">Family Plan</option>
            </select>
            <br><br>
            <button type="submit" name="membership">Get Membership</button>
            <br><br><br>
    </form>

        <?php elseif ($page == 'personal_training'): ?>
            <h2>Personal Training</h2>
            <br><br>
        <p>Our certified trainers are here to help you achieve your fitness goals with personalized training programs.</p><br><br><br>
        <ul>
            <li><strong>Trainer 1:</strong><br> Mr. S.K.D.Karunarathne <br> Expertise in weight loss and nutrition.</li><br><br>
            <li><strong>Trainer 2:</strong><br> Ms. Asheka Shashini <br> Mr. Awishka Dias <br> Specializes in strength training and bodybuilding.</li><br><br>
            <li><strong>Trainer 3:</strong><br>Mr. Kavidu Shriyan <br> Mr. Abhisheka Rathnayake <br> Mrs. Kaveesha Fernando <br> Focuses on functional fitness and rehabilitation.</li>
        </ul>
        <br><br><br>

        <h3><i>Pricing Packages:</i></h3><br><br>
        <ul>
            <li>1-on-1 Training : LKR 2,000/session</li><br>
            <li>Group Training : LKR 1,500/person</li><br>
            <li>Monthly Package : LKR 16,000 for 10 sessions</li>
            <br><br><br>
        </ul>

        <?php elseif ($page == 'about_us'): ?>
    <h2>What is the FitZone Fitness Center</h2>
    <br><br>
    <p><b><i>Our Vision</i></b>
    <br>
          To become Kurunegala's premier fitness destination, inspiring a healthier community and empowering individuals to achieve their fitness goals through innovative classes, personalized training, and a supportive environment.
          <br><br>
         <b><i>Our Mission</i></b>
         <br>
           FitZone Fitness Center is committed to enhancing the health and well-being of our members by providing high-quality fitness programs, certified trainers, and a welcoming space where everyone—from beginners to fitness enthusiasts—feels encouraged, motivated, and valued. We aim to create a fitness experience that fosters positive lifestyle changes, elevates personal confidence, and builds a strong, supportive community.
           <br><br></p>

           <h3><i>Our Blog </i></h3>
    <p>Check out our latest blog posts on workout routines, healthy recipes, and success stories:</p>
    <br><br>
    <img src="blog.jpg" class="blog-image"><br>
    <img src="workout.jpg" class="blog-image"><br>
    <img src="meal-plan.jpg" class="blog-image"><br>
    <img src="success-story.jpg" class="blog-image"><br>
    <br><br><br>

    <h3><i>Reviews</i></h3>
    <img src = "reviews.jpg" class = "blog-image">
    <br><br><br>

    <h3><i> Contact Us : </i></h3>
     <p> 32/A , Main street , Kurunegala. <br><br>
   037-2231234 / 076-0465467 </p><br><br>

<h3><b> Follow Us : </b></h3><br>
<img src = "follow-us.jpg" class = "follow-image">
<br><br><br>


    <?php elseif ($page == 'register'): ?>
        <h2>Register</h2>
        <br><br><br>
        <form method="post">
            <input type="text" name="reg_username" placeholder="Username" required><br><br>
            <input type="email" name="reg_email" placeholder="Email" required><br><br>
            <input type="password" name="reg_password" placeholder="Password" required><br><br>
            <button type="submit" name="register">Register</button><br>
            <br><br><br>
        </form>

    <?php elseif ($page == 'login'): ?>
        <h2>Login</h2>
        <br><br><br>
        <form method="post">
            <input type="text" name="login_username" placeholder="Username" required><br><br>
            <input type="password" name="login_password" placeholder="Password" required><br><br>
            <button type="submit" name="login">Login</button>
            <br><br><br>
        </form>

        <?php elseif ($page == 'book_class'): ?>
        <h2>Book a Class</h2>
        <br><br><br>
        <form method="post">
            <input type="text" name="full_name" placeholder="Full Name" required><br><br>
            <input type="text" name="contact" placeholder="Contact Number" required><br><br>
            <select name="class_name">
                <option value="" disabled selected>Select Class</option>
                <option value="Cardio">Cardio</option>
                <option value="Strength Training">Strength Training</option>
                <option value="Yoga">Yoga</option>
                <option value = "Cycling"> Cycling </option>
                <option value = "HIIT"> HIIT </option>
            </select>
            <br><br>
            <button type="submit" name="book_class">Book Class</button>
            <br><br><br>
        </form>
        </div>

    <?php elseif ($page == 'submit_query'): ?>
        <h2>FAQ</h2>
        <br><br><br>
        <div class = "questions">
       <p><b> Q: What are the operating hours of FitZone Fitness Center?</b><br>
<b>Answer:</b> FitZone Fitness Center is open from 5:00 AM to 10:00 PM, Monday to Saturday. We are closed on Sundays and public holidays.
<br><br>

<b>Q: Do I need to register for a class in advance?</b><br>
<b>Answer:</b> Yes, we recommend booking your class in advance to secure a spot. You can easily book classes online through our website after registering and logging in.
<br><br>

<b>Q: What types of classes do you offer?</b><br>
<b>Answer</b>: We offer a variety of classes, including Cardio, Strength Training, Yoga, Cycling, and High-Intensity Interval Training (HIIT). You can find more details on the Classes page.
<br><br>

<b>Q: Can I bring a guest to the gym?</b><br>
<b>Answer:</b> Yes, members can bring a guest for a one-time visit. However, guests must sign in at the front desk and may be subject to a guest fee.
<br><br>

<b>Q: Is personal training available?</b><br>
<b>Answer:</b> Yes, we offer personal training sessions with certified trainers who specialize in various fitness goals. You can learn more and sign up on our Personal Training page.
<br><br>
    </p>
    </div>
    <div class = "submit">
        <form method="post">
            <input type="text" name="name" placeholder="Your Name" required><br><br>
            <input type="email" name="email" placeholder="Your Email" required><br><br>
            <textarea name="message" placeholder="Your Message" required></textarea><br><br>
            <button type="submit" name="submit_query">Submit Query</button>
            <br><br><br>
        </form>
        <?php endif; ?>
       
        <footer>
        <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
    </footer>
</div>

<script>
    // script.js
document.addEventListener("DOMContentLoaded", function() {
    const bookButtons = document.querySelectorAll(".book-class");
    const modal = document.getElementById("booking-modal");
    const closeModal = document.querySelector(".close");
    const bookingForm = document.getElementById("booking-form");
    const classNameInput = document.getElementById("class-name");

    bookButtons.forEach(button => {
        button.addEventListener("click", function() {
            classNameInput.value = this.dataset.class; 
            modal.style.display = "block"; // Show the modal
        });
    });

    closeModal.onclick = function() {
        modal.style.display = "none"; // Hide the modal
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none"; // Hide the modal if clicked outside
        }
    };

    bookingForm.onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission
        const formData = new FormData(bookingForm); // Create FormData object

        fetch("process_booking.php", {
            method: "POST",
            body: formData // Send the form data
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show success message
            modal.style.display = "none"; // Close the modal
            bookingForm.reset(); // Reset the form
        })
        .catch(error => console.error("Error:", error)); // Log any errors
    };
});    
</script>     
</body>
</html>
