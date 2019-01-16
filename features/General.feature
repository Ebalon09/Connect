Feature: General Test of Twitter application
  This Feature tests the general features of our Twitter application

  Scenario: Register
    Given I am on "http://twitter.php72/"
    Then I should see "Register"
    Then I follow "Register"
    And I wait 15 seconds for request to complete
    And I should see "Sign up"
    And I should see "USERNAME"
    And I should see "EMAIL"
    And I should see "PASSWORD"
    And I should see "IMAGE"
    And I should see "Remember me"
    And I should see "Already Have Account ?"
    Then I fill in "EMAIL" with "Behattest@behat.de"
    Then I fill in "USERNAME" with "Behat"
    Then I fill in "PASSWORD" with "Behat"
    And I wait 2 seconds for request to complete
    Then I press "signupButton"
    And I wait 3 seconds for request to complete
    Then I should see "TwitterClone"

  Scenario: Login
    Given I am on "http://twitter.php72/"
    Then I should see "TwitterClone"
    And I should see "Register"
    And I should see "homeButton"
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
    And I press "Logout"
    Then I should not see "Logout"

  Scenario: LoggedOutFeedTest
    Given I am on "http://twitter.php72/"
    Then I should see "TwitterClone"
    And I should see "homeButton"
    And I should see "Register"
    And I should see "Username"
    And I should see "Password"
    And I should see "Login"
    And I should see "Trends"
    And I should see "UserInput_disabled"
    And I should see "Vorgeschlagen"
    And I should see "Hier ist das Ende :("

  Scenario: LoggedInFeedTest
    Given I am on "http://twitter.php72/"
    And I am logged in as "Behat" with "Behat"
    Then I should see "Erfolgreich angemeldet!"
    And I should see "TwitterClone"
    And I should see "homeButton"
    And I should see "Optionen"
    And I should see "Angemeldet als : Behat"
    And I should see "Logout"
    And I should see an "#profileImage" element
    And I should see "TWEETS"
    And I should see "FOLLOWING"
    And I should see "FOLLOWERS"
    And I should see "Trends"
    And I should see "Post"
    And I should see "Vorgeschlagen"
    And I should see "Hier ist das Ende :("

  Scenario: PostingTweet
    Given I am on "http://twitter.php72/"
    And I am logged in as "Behat" with "Behat"
    Then I should see "Erfolgreich angemeldet!"
    Then I fill in "Userinput" with "This is a Behat test"
    And I focus on Userinput
    Then I submit the field Userinput
    Then I should see "Tweet erfolgreich gepostet"

    Scenario: LikeTest
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      And I should see an "#like" element
      And I follow "like"
      Then I should see an "#dislike" element
      Then I follow "dislike"
      And I should see an "#like" element

    Scenario: EditTest
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see an "#edit" element
      And I follow "edit"
      Then I focus on Userinput
      And I fill in "Userinput" with "Behat Edit"
      And I submit the field Userinput
      Then I should see "Eintrag erfolgreich geupdatet"

    Scenario: Post ReTweet Test
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see an "#reTweet" element
      And I follow "reTweet"
      Then I focus on "Userinput"
      And I fill in "Userinput" with "Behat reTweet"
      And I submit the field Userinput
      Then I should see "Tweet erfolgreich gepostet"
      And I follow "delete"
      Then I should see "Tweet erfolgreich gelöscht"

    Scenario: PostCommentFromFeed
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see an "#comment" element
      And I follow "comment"
      Then I focus on "commentinput"
      And I fill in "commentinput" with "Behat comment"
      And I submit the field commentinput
      Then I should see "Kommentar erfolgreich gepostet"

    Scenario: CommentFeedTest
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see an "#commentCounter" element
      And I follow "commentCounter"
      Then I should see "Userinput"
      And I fill in "Userinput" with "This is a Behat testComment from the commentFeed"
      And I focus on Userinput
      Then I submit the field Userinput
      Then I should see "Kommentar erfolgreich gepostet"
      Then I should see an "#edit" element
      And I follow "edit"
      Then I focus on Userinput
      And I fill in "Userinput" with "Behat comment Edit"
      And I submit the field Userinput
      Then I should see "Tweet erfolgreich gepostet"
      Then I should see an "#delete" element
      And I follow "delete"
      Then I should see "Tweet erfolgreich gelöscht"

    Scenario: PostDelete
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see an "#delete" element
      And I follow "delete"
      Then I should see "Tweet erfolgreich gelöscht"

    Scenario: SettingsEmail
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "email" with "Behat@behat2.de"
      Then I press "next"
      And I wait 2 seconds for request to complete
      Then I verify my password with "Behat"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"

    Scenario: SettingsUsername
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "username" with "Behat2"
      Then I press "next"
      And I wait 2 seconds for request to complete
      Then I verify my password with "Behat"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"


    Scenario: SettingsPassword
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat2" with "Behat"
      Then I should see "Erfolgreich angemeldet!"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Einstellungen"
      And I follow "Einstellungen"
      Then I should see "Settings"
      Then I fill in "passwordchange" with "Behat2"
      Then I press "next"
      And I wait 2 seconds for request to complete
      Then I verify my password with "Behat"
      Then I should see "erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt"


  Scenario: DeleteAccount
      Given I am on "http://twitter.php72/"
      And I am logged in as "Behat2" with "Behat2"
      Then I should see "Erfolgreich angemeldet!"
      And I should see "Angemeldet als : Behat"
      And I should see "Logout"
      Then I should see "Optionen"
      And I press "Optionen"
      Then I should see "Account Löschen"
      Then I follow "Account Löschen"
      Then I should see "Register"