<?php  
session_start();  
require '../includes/dbconnection.php'; // Ensure your dbconnection.php uses mysqli connection  

// Initialize error variable  
$error = '';  

// Process the form when it's submitted  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    // Sanitize and trim input values  
    $full_name = trim($_POST['username']);  
    $password = md5(trim($_POST['password'])); // Hash the password using MD5  
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);  
    $sex = trim($_POST['sex']);  
    $phone_number = trim($_POST['phone_number']);  
    $nationality = trim($_POST['nationality']);  
    $city = trim($_POST['city']);  

    // Check if the email already exists  
    $query = "SELECT * FROM users WHERE email = ?";  
    $stmt = $con->prepare($query);  
    $stmt->bind_param("s", $email);  
    $stmt->execute();  
    $result = $stmt->get_result();  

    if ($result->num_rows > 0) {  
        $error = "Email already exists!";  
    } else {  
        // Insert new user into the database with role_id = 1  
        $query = "INSERT INTO users (full_name, email, password, sex, phone_number, nationality, city, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";   
        $stmt = $con->prepare($query);  
        $stmt->bind_param("sssssss", $full_name, $email, $password, $sex, $phone_number, $nationality, $city);  

        if ($stmt->execute()) {  
            header('Location: login.php'); // Redirect to the login page on successful registration  
            exit;  
        } else {  
            $error = "Error in registration. Please try again!";  
        }  
    }  
}  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="style.css">  
    <link rel="stylesheet" href="../user/bootstrap.css">
    <title>MTDH | Register</title>  
</head>  
<body>  
    <div class="form-container">  
        <h2>Create MTDH Account</h2>  
        <form method="post" action="" class="container">  
            <div class="form-group">  
                <input type="text" name="username" placeholder="Full Name" required>  
                <input type="email" name="email" placeholder="Email" required>  
            </div>  
            <div class="form-group">  
                <input type="text" name="phone_number" placeholder="Phone Number" required>  
                <select name="nationality" required> 
                    <option value="" disabled selected>Nationality</option>   
                    <option value="Afghanistan">Afghanistan</option>  
                    <option value="Albania">Albania</option>  
                    <option value="Algeria">Algeria</option> 
                    <option value="Andorra">Andorra</option>  
                        <option value="Angola">Angola</option>  
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>  
                        <option value="Argentina">Argentina</option>  
                        <option value="Armenia">Armenia</option>  
                        <option value="Australia">Australia</option>  
                        <option value="Austria">Austria</option>  
                        <option value="Azerbaijan">Azerbaijan</option>  
                        <option value="Bahamas">Bahamas</option>  
                        <option value="Bahrain">Bahrain</option>  
                        <option value="Bangladesh">Bangladesh</option>  
                        <option value="Barbados">Barbados</option>  
                        <option value="Belarus">Belarus</option>  
                        <option value="Belgium">Belgium</option>  
                        <option value="Belize">Belize</option>  
                        <option value="Benin">Benin</option>  
                        <option value="Bhutan">Bhutan</option>  
                        <option value="Bolivia">Bolivia</option>  
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>  
                        <option value="Botswana">Botswana</option>  
                        <option value="Brazil">Brazil</option>  
                        <option value="Brunei">Brunei</option>  
                        <option value="Bulgaria">Bulgaria</option>  
                        <option value="Burkina Faso">Burkina Faso</option>  
                        <option value="Burundi">Burundi</option>  
                        <option value="Cabo Verde">Cabo Verde</option>  
                        <option value="Cambodia">Cambodia</option>  
                        <option value="Cameroon">Cameroon</option>  
                        <option value="Canada">Canada</option>  
                        <option value="Central African Republic">Central African Republic</option>  
                        <option value="Chad">Chad</option>  
                        <option value="Chile">Chile</option>  
                        <option value="China">China</option>  
                        <option value="Colombia">Colombia</option>  
                        <option value="Comoros">Comoros</option>  
                        <option value="Congo (Congo-Brazzaville)">Congo (Congo-Brazzaville)</option>  
                        <option value="Costa Rica">Costa Rica</option>  
                        <option value="Croatia">Croatia</option>  
                        <option value="Cuba">Cuba</option>  
                        <option value="Cyprus">Cyprus</option>  
                        <option value="Czech Republic">Czech Republic</option>  
                        <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>  
                        <option value="Denmark">Denmark</option>  
                        <option value="Djibouti">Djibouti</option>  
                        <option value="Dominica">Dominica</option>  
                        <option value="Dominican Republic">Dominican Republic</option>  
                        <option value="Ecuador">Ecuador</option>  
                        <option value="Egypt">Egypt</option>  
                        <option value="El Salvador">El Salvador</option>  
                        <option value="Equatorial Guinea">Equatorial Guinea</option>  
                        <option value="Eritrea">Eritrea</option>  
                        <option value="Estonia">Estonia</option>  
                        <option value="Eswatini">Eswatini</option>  
                        <option value="Ethiopia">Ethiopia</option>  
                        <option value="Fiji">Fiji</option>  
                        <option value="Finland">Finland</option>  
                        <option value="France">France</option>  
                        <option value="Gabon">Gabon</option>  
                        <option value="Gambia">Gambia</option>  
                        <option value="Georgia">Georgia</option>  
                        <option value="Germany">Germany</option>  
                        <option value="Ghana">Ghana</option>  
                        <option value="Greece">Greece</option>  
                        <option value="Grenada">Grenada</option>  
                        <option value="Guatemala">Guatemala</option>  
                        <option value="Guinea">Guinea</option>  
                        <option value="Guinea-Bissau">Guinea-Bissau</option>  
                        <option value="Guyana">Guyana</option>  
                        <option value="Haiti">Haiti</option>  
                        <option value="Honduras">Honduras</option>  
                        <option value="Hungary">Hungary</option>  
                        <option value="Iceland">Iceland</option>  
                        <option value="India">India</option>  
                        <option value="Indonesia">Indonesia</option>  
                        <option value="Iran">Iran</option>  
                        <option value="Iraq">Iraq</option>  
                        <option value="Ireland">Ireland</option>  
                        <option value="Israel">Israel</option>  
                        <option value="Italy">Italy</option>  
                        <option value="Jamaica">Jamaica</option>  
                        <option value="Japan">Japan</option>  
                        <option value="Jordan">Jordan</option>  
                        <option value="Kazakhstan">Kazakhstan</option>  
                        <option value="Kenya">Kenya</option>  
                        <option value="Kiribati">Kiribati</option>  
                        <option value="Kuwait">Kuwait</option>  
                        <option value="Kyrgyzstan">Kyrgyzstan</option>  
                        <option value="Laos">Laos</option>  
                        <option value="Latvia">Latvia</option>  
                        <option value="Lebanon">Lebanon</option>  
                        <option value="Lesotho">Lesotho</option>  
                        <option value="Liberia">Liberia</option>  
                        <option value="Libya">Libya</option>  
                        <option value="Liechtenstein">Liechtenstein</option>  
                        <option value="Lithuania">Lithuania</option>  
                        <option value="Luxembourg">Luxembourg</option>  
                        <option value="Madagascar">Madagascar</option>  
                        <option value="Malawi">Malawi</option>  
                        <option value="Malaysia">Malaysia</option>  
                        <option value="Maldives">Maldives</option>  
                        <option value="Mali">Mali</option>  
                        <option value="Malta">Malta</option>  
                        <option value="Marshall Islands">Marshall Islands</option>  
                        <option value="Mauritania">Mauritania</option>  
                        <option value="Mauritius">Mauritius</option>  
                        <option value="Mexico">Mexico</option>  
                        <option value="Micronesia">Micronesia</option>  
                        <option value="Moldova">Moldova</option>  
                        <option value="Monaco">Monaco</option>  
                        <option value="Mongolia">Mongolia</option>  
                        <option value="Montenegro">Montenegro</option>  
                        <option value="Morocco">Morocco</option>  
                        <option value="Mozambique">Mozambique</option>  
                        <option value="Myanmar">Myanmar</option>  
                        <option value="Namibia">Namibia</option>  
                        <option value="Nauru">Nauru</option>  
                        <option value="Nepal">Nepal</option>  
                        <option value="Netherlands">Netherlands</option>  
                        <option value="New Zealand">New Zealand</option>  
                        <option value="Nicaragua">Nicaragua</option>  
                        <option value="Niger">Niger</option>  
                        <option value="Nigeria">Nigeria</option>  
                        <option value="North Korea">North Korea</option>  
                        <option value="North Macedonia">North Macedonia</option>  
                        <option value="Norway">Norway</option>  
                        <option value="Oman">Oman</option>  
                        <option value="Pakistan">Pakistan</option>  
                        <option value="Palau">Palau</option>  
                        <option value="Palestine State">Palestine State</option>  
                        <option value="Panama">Panama</option>  
                        <option value="Papua New Guinea">Papua New Guinea</option>  
                        <option value="Paraguay">Paraguay</option>  
                        <option value="Peru">Peru</option>  
                        <option value="Philippines">Philippines</option>  
                        <option value="Poland">Poland</option>  
                        <option value="Portugal">Portugal</option>  
                        <option value="Qatar">Qatar</option>  
                        <option value="Romania">Romania</option>  
                        <option value="Russia">Russia</option>  
                        <option value="Rwanda">Rwanda</option>  
                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>  
                        <option value="Saint Lucia">Saint Lucia</option>  
                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>  
                        <option value="Samoa">Samoa</option>  
                        <option value="San Marino">San Marino</option>  
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>  
                        <option value="Saudi Arabia">Saudi Arabia</option>  
                        <option value="Senegal">Senegal</option>  
                        <option value="Serbia">Serbia</option>  
                        <option value="Seychelles">Seychelles</option>  
                        <option value="Sierra Leone">Sierra Leone</option>  
                        <option value="Singapore">Singapore</option>  
                        <option value="Slovakia">Slovakia</option>  
                        <option value="Slovenia">Slovenia</option>  
                        <option value="Solomon Islands">Solomon Islands</option>  
                        <option value="Somalia">Somalia</option>  
                        <option value="South Africa">South Africa</option>  
                        <option value="South Korea">South Korea</option>  
                        <option value="South Sudan">South Sudan</option>  
                        <option value="Spain">Spain</option>  
                        <option value="Sri Lanka">Sri Lanka</option>  
                        <option value="Sudan">Sudan</option>  
                        <option value="Suriname">Suriname</option>  
                        <option value="Sweden">Sweden</option>  
                        <option value="Switzerland">Switzerland</option>  
                        <option value="Syria">Syria</option>  
                        <option value="Taiwan">Taiwan</option>  
                        <option value="Tajikistan">Tajikistan</option>  
                        <option value="Tanzania">Tanzania</option>  
                        <option value="Thailand">Thailand</option>  
                        <option value="Togo">Togo</option>  
                        <option value="Tonga">Tonga</option>  
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>  
                        <option value="Tunisia">Tunisia</option>  
                        <option value="Turkey">Turkey</option>  
                        <option value="Turkmenistan">Turkmenistan</option>  
                        <option value="Tuvalu">Tuvalu</option>  
                        <option value="Uganda">Uganda</option>  
                        <option value="Ukraine">Ukraine</option>  
                        <option value="United Arab Emirates">United Arab Emirates</option>  
                        <option value="United Kingdom">United Kingdom</option>  
                        <option value="United States">United States</option>  
                        <option value="Uruguay">Uruguay</option>  
                        <option value="Uzbekistan">Uzbekistan</option>  
                        <option value="Vanuatu">Vanuatu</option>  
                        <option value="Vatican City">Vatican City</option>  
                        <option value="Venezuela">Venezuela</option>  
                        <option value="Vietnam">Vietnam</option>  
                        <option value="Yemen">Yemen</option>  
                        <option value="Zambia">Zambia</option>  
                        <option value="Zimbabwe">Zimbabwe</option> 
                </select>  
            </div>    
            <div class="form-group">  
                <input type="text" class="form-control" id="city" name="city" placeholder="City" required>  
                <select class="form-control" id="sex" name="sex" required>  
                    <option value="" disabled selected>Select your gender</option>  
                    <option value="Male">Male</option>  
                    <option value="Female">Female</option>  
                    <option value="Other">Other</option>  
                </select>  
            </div>  
            <div class="form-group">  
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>  
            </div>  
            <button type="submit" class="btn btn-primary">Register</button>
            <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>  
            <p class="footer-links">Already have an account? <a href="login.php">Login Now</a></p>    
        </form>  
    </div>  
</body>  
</html>  
