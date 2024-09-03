<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #f0f2f5;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #1877f2;">
        <a class="navbar-brand d-flex align-items-center" href="#" style="color: #ffffff;">
            <i class="fab fa-facebook-f" style="font-size: 30px; color: blue; padding: 5px; border-radius: 50%;"></i>
            <span style="font-weight: bold;">Facebook</span>
        </a>
    </nav>

    <div class="container mt-5" style="max-width: 500px; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 class="text-center" style="margin-bottom: 20px;">Create a new account</h2>
         <p class="text-center">It's quick and easy.</p>
         <hr>
        <form action="Database.php" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" required>
                    <?php if (isset($errors['firstname'])) : ?>
                        <li class="text-red-500 text-xs mt-2"><?= $errors['firstname'] ?></li>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last Name" required>
                    <?php if (isset($errors['lastname'])) : ?>
                        <li class="text-red-500 text-xs mt-2"><?= $errors['lastname'] ?></li>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                <?php if (isset($errors['email'])) : ?>
                        <li class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></li>
                    <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <?php if (isset($errors['password'])) : ?>
                        <li class="text-red-500 text-xs mt-2"><?= $errors['password'] ?></li>
                    <?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <input type="number" id="dob-day" name="dob-day" class="form-control" placeholder="Day" min="1" max="31" required>
                </div>
                <div class="form-group col-md-4">
                    <select id="dob-month" name="dob-month" class="form-control" required>
                        <option value="" disabled selected>Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <input type="number" id="dob-year" name="dob-year" class="form-control" placeholder="Year" min="1980" max="2024" required>
                </div>
                
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="male" name="gender" value="male" required>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="female" name="gender" value="female" required>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="other" name="gender" value="other" required>
                    <label class="form-check-label" for="other">Other</label>
                </div>
                <div class="form-group mt-3" id="other-gender-group" style="display: none;">
                    <input type="text" id="other-gender-detail" name="other-gender-detail" class="form-control" placeholder="gender(optional)">
                </div>
            </div>

        <div class="form-group">
          <select id="detailSelect" name="nickname" class="form-control">
           <option value="" disabled selected>Select nickname</option>
           <option value="peg">Peg</option>
           <option value="rat">Rat</option>
          </select>
         </div>

            <div >
              <p><small>People who use our service may have uploaded your contact information to Facebook. <a href="#">Learn more </a>.</small></p>
            </div>
            <div>   <small>
            By clicking Register, you agree to our <a href="">terms </a> , 
            <a href="" >Privacy Policy</a> and
          <a href="" > Cookies Policy</a>
             . You can receive SMS notifications from us and you can stop receiving notifications at any time.
            <small>
          </div>
             <div class="mt-3"> 
            <button type="submit" class="btn btn-primary btn-block" style="background-color: green; border-color: #1877f2;">record</button>
            </div>

            <div class="text-center mt-3" >
               <a href="login.view.php"><h2> Already have a account<h2> </a>
            </div>
        </form>
    </div>

    <?php require "Partials/script.php" ?>

    <script>
        document.querySelectorAll('input[name="gender"]').forEach((elem) => {
            elem.addEventListener("change", function() {
                var otherGenderGroup = document.getElementById('other-gender-group');
                if (this.value === 'other') {
                    otherGenderGroup.style.display = 'block';
                } else {
                    otherGenderGroup.style.display = 'none';
                }
            });
        });
    </script>

 
</body>
</html>
