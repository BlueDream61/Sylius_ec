@managing_product_attributes
Feature: Adding a new percent product attribute
    In order to show specific product's parameters to customer
    As an Administrator
    I want to add a new percent product attribute

    Background:
        Given the store is available in "English (United States)"
        And I am logged in as an administrator

    @todo @ui @api
    Scenario: Adding a new percent product attribute
        When I want to create a new percent product attribute
        And I specify its code as "t_shirt_cotton_content"
        And I name it "T-Shirt cotton content" in "English (United States)"
        And I add it
        Then I should be notified that it has been successfully created
        And the percent attribute "T-Shirt cotton content" should appear in the store

    @todo @ui @no-api
    Scenario: Seeing disabled type field while adding a percent product attribute
        When I want to create a new percent product attribute
        Then the type field should be disabled
