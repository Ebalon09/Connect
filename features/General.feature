Feature: General Test of Twitter application
  This Feature tests the general features of our Twitter application


  Scenario: Register
    Given I am on "http://localhost/test/index.php"
    Then I should see "Register"
    Then I follow "Register"
    And I wait 15 seconds for request to complete
    And I should see "Sign up"
    And I should see "USERNAME"
    And I should see "EMAIL"
    And I should see "PASSWORD"
    And I should see "IMAGE"
    Then I fill in "EMAIL" with "Behattest@behat.de"
    Then I fill in "USERNAME" with "Behat"
    Then I fill in "PASSWORD" with "Behat"
    And I wait 5 seconds for request to complete
    Then I press "signupButton"
    And I wait 10 seconds for request to complete
    Then I should see "TwitterClone"

  Scenario: Login
    Given I am on "http://localhost/test/index.php"
    Then I should see "TwitterClone"
    And I should see "Register"
    And I should see "Username"
    And I should see "Password"
    And I should see "Login"
    Then I fill in "Username" with "Behat"
    Then I fill in "Password" with "Behat"
    Then I press "Login"
    Then I should see "Erfolgreich angemeldet!"
    And I should see "Optionen"
    And I should see "Angemeldet als : Behat"
    And I should see "Logout"

  Scenario: Test Mainpage
    Given I am on "http://localhost/test/index.php"
    Then I should see "TwitterClone"
    And I should see "Register"
    And I should see "Username"
    And I should see "Password"
    And I should see "Login"
    Then I fill in "Username" with "Behat"
    Then I fill in "Password" with "Behat"
    Then I press "Login"
    Then I should see "Erfolgreich angemeldet!"
    And I should see "Optionen"
    And I should see "Angemeldet als : Behat"
    And I should see "Logout"
    And I should see "Trends"
    And I should see "TWEETS"
    And I should see "FOLLOWING"
    And I should see "FOLLOWERS"
    And I should see "Vorgeschlagen"
    And I should see "Hier ist das Ende :("
    And I should see "Post"
    Then I fill in "Userinput" with "This is a Behat test"
    And I focus on Userinput
    Then I submit the field Userinput
    Then I should see "Tweet erfolgreich gepostet"

  Scenario: Test Logout
      Then I should see "Logout"
      And I press "Logout"
      Then I should not see "Logout"
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      Then I should see "Logout"

    Scenario: EditTest
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      And I follow "edit"
      Then I focus on "Userinput"
      And I fill in "Userinput" with "Behat Edit"
      And I submit the field Userinput
      Then I should see "Eintrag erfolgreich geupdatet"

    Scenario: Post ReTweet Test
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      And I follow "reTweet"
      Then I focus on "Userinput"
      And I fill in "Userinput" with "Behat reTweet"
      And I submit the field Userinput
      Then I should see "Tweet erfolgreich gepostet"

    Scenario: PostComment
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : behat"
      And I should see "Logout"
      And I follow "comment"
      Then I focus on "commentinput"
      And I fill in "commentinput" with "Dies ist ein Behat test Kommentar"
      And I submit the field commentinput
      Then I should see "Kommentar erfolgreich gepostet"

    Scenario: CommentFeed
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      Then I follow "commentCounter"
      And I should see "Userinput"
      Then I fill in "Userinput" with "This is a Behat testComment from the commentFeed"
      And I focus on Userinput
      Then I submit the field Userinput
      Then I should see "Kommentar erfolgreich gepostet"
      And I should see "edit"
      And I follow "edit"
      Then I focus on "Userinput"
      And I fill in "Userinput" with "Behat Edit"
      And I submit the field Userinput
      Then I should see "Tweet erfolgreich gepostet"
      And I should see "delete"
      And I follow "delete"
      Then I should see "Tweet erfolgreich gelöscht"
      And I follow "Home"


    Scenario: PostDelete
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Optionen"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      And I follow "delete"
      Then I should see "Tweet erfolgreich gelöscht"

    Scenario: SettingsEmail
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "email" with "Behat@behat2.de"
      Then I press "next"
      And I wait 5 seconds for request to complete
      Then I fill in "PasswordVerify" with "Behat"
      Then I fill in "rePasswordVerify" with "Behat"
      Then I submit the field "PasswordVerify"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"

    Scenario: SettingsUsername
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "username" with "Behat2"
      Then I press "next"
      And I wait 5 seconds for request to complete
      Then I fill in "PasswordVerify" with "Behat"
      Then I fill in "rePasswordVerify" with "Behat"
      Then I submit the field "PasswordVerify"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"


    Scenario: SettingsPassword
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat2"
      Then I fill in "Password" with "Behat"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "passwordchange" with "Behat2"
      Then I press "next"
      And I wait 5 seconds for request to complete
      Then I fill in "PasswordVerify" with "Behat"
      Then I fill in "rePasswordVerify" with "Behat"
      Then I submit the field "PasswordVerify"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"


  Scenario: DeleteAccount
      Given I am on "http://localhost/test/index.php"
      Then I should see "TwitterClone"
      And I should see "Register"
      And I should see "Username"
      And I should see "Password"
      And I should see "Login"
      Then I fill in "Username" with "Behat2"
      Then I fill in "Password" with "Behat2"
      Then I press "Login"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Account Löschen"
      Then I follow "Account Löschen"
      Then I should see "Register"