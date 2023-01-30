@managing_orders
Feature: Being unable to see details of a cart
    In order to see details only of a placed order
    As an Administrator
    I want to be unable to view basic information about a cart

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "PHP T-Shirt" priced at "$12.00"
        And the customer has created empty cart
        And the customer has product "PHP T-Shirt" in the cart
        And I am logged in as an administrator

    @ui
    Scenario: Seeing basic information about an order
        When I try to view the summary of the customer's cart
        Then I should be informed that the order does not exist
