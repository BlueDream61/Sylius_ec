@managing_shipping_methods
Feature: Shipping method unique code validation
    In order to uniquely identify shipping methods
    As an Administrator
    I want to be prevented from adding two shipping methods with same code

    Background:
        Given the store is available in "English (United States)"
        And there is a zone "EU" containing all members of the European Union
        And the store allows shipping with "UPS Ground" identified by "UPS"
        And I am logged in as an administrator

    @ui @javascript
    Scenario: Trying to add shipping method with taken code
        Given I want to create a new shipping method
        When I specify its code as "UPS"
        And I name it "FedEx Carrier" in "English (United States)"
        And I define it for the "European Union" zone
        And I choose "Flat rate per shipment" calculator
        And I specify its amount as 50
        And I try to add it
        Then I should be notified that shipping method with this code already exists
        And there should still be only one shipping method with code "UPS"
