@managing_promotions
Feature: Adding a new promotion with action
    In order to give possibility to pay specifically less price for some goods
    As an Administrator
    I want to add a new promotion with action to the registry

    Background:
        Given the store operates on a single channel in "France"
        And I am logged in as an administrator

    @ui @javascript
    Scenario: Adding a new promotion with fixed discount
        Given I want to create a new promotion
        When I specify its code as "10_for_all_products"
        And I name it "€10.00 for all products!"
        And I add the "Order fixed discount" action configured with €10.00
        And I add it
        Then I should be notified that it has been successfully created
        And the promotion "€10.00 for all products!" should appear in the registry
