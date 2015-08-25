@reviews
Feature: Reviews
    In orders to know customer's opinions about my products
    As a store owner
    I want to be able to manage reviews

    Background:
        Given there are following users:
            | email          |
            | beth@foo.com   |
            | martha@foo.com |
            | rick@foo.com   |
        And there are following taxonomies defined:
            | name     |
            | Category |
        And taxonomy "Category" has following taxons:
            | Clothing > T-Shirts     |
            | Clothing > PHP T-Shirts |
            | Clothing > Gloves       |
        And the following products exist:
            | name             | price | taxons       | average rating |
            | Super T-Shirt    | 19.99 | T-Shirts     | 0              |
            | Black T-Shirt    | 18.99 | T-Shirts     | 4              |
            | Sylius Tee       | 12.99 | PHP T-Shirts | 5              |
            | Symfony T-Shirt  | 15.00 | PHP T-Shirts | 1              |
            | Doctrine T-Shirt | 15.00 | PHP T-Shirts | 0              |
        And there are following reviews:
            | title       | rating | comment               | author         | product         |
            | Really bad  | 1      | Lorem ipsum dolor sit | beth@foo.com   | Symfony T-Shirt |
            | Very good   | 4      | Lorem ipsum dolor sit | martha@foo.com | Black T-Shirt   |
            | Awesome     | 5      | Lorem ipsum dolor sit | rick@foo.com   | Sylius Tee      |
        And there is default currency configured
        And there is default channel configured
        And I am logged in as administrator

    Scenario: Seeing created reviews in the list
        Given I am on the dashboard page
         When I follow "Reviews"
         Then I should see 3 reviews in the list

    Scenario: Adding new review with default options
        Given I am on the review creation page
         When I fill in the following:
            | Title   | New review        |
            | Comment | Lorem ipsum dolor |
          And I select the "5" radio button
          And I press "Create"
         Then I should be on the page of review with title "New review"
          And I should see "Review has been successfully created."
          And I should see "Lorem ipsum dolor"

    Scenario: Prevent adding review without required fields
        Given I am on the review creation page
         When I press "Create"
         Then I should still be on the review creation page
          And I should see "You must check review rating."
          And I should see "Review title should not be blank."

    Scenario: Accessing review edit page from the list
        Given I am on the review index page
         When I press "edit" near "Very good"
         Then I should be editing review with title "Very good"

    Scenario: Accessing review edit page from details page
        Given I am on the page of review with title "Very good"
         When I follow "edit"
         Then I should be editing review with title "Very good"

    Scenario: Updating review
        Given I am editing review with title "Very good"
         When I fill in "Title" with "Very, very good"
          And I press "Save changes"
         Then I should be on the page of review with title "Very, very good"

    @javascript
    Scenario: Removing review from the list
        Given I am on the review index page
         When I press "delete" near "Awesome"
         Then I should still be on the review index page
          And I should see "Review has been successfully deleted."

    @javascript
    Scenario: Removing review from details page
        Given I am on the page of review with title "Awesome"
         When I press "delete"
         Then I should be on the review index page
          And I should see "Review has been successfully deleted."

    Scenario: Showing review details
        Given I am on the review index page
         When I click "details" near "Awesome"
         Then I should be on the page of review with title "Awesome"
          And I should see "Awesome"
          And I should see "rick@foo.com"
          And I should see "Lorem ipsum dolor"
