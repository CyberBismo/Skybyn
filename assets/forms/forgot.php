                <h2>Forgot your password?</h2>
                <form method="post">
                    
                    <i class="fa-solid fa-at"></i>
                    <input type="email" name="email" placeholder="Enter e-mail address" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid email address')" autofocus>

                    <input type="submit" name="forgot" value="Request password reset">
                </form>
                <div class="links">
                    <button onclick="window.location.href='./'">Go back</a>
                </div>