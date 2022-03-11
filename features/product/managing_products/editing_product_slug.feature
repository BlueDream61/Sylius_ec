@managing_products
Feature: Editing product's slug
    In order to manage access path to product page
    As an Administrator
    I want to be able to edit product's slug

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui @javascript @api
    Scenario: Creating a product with an autogenerated slug
        When I want to create a new simple product
        And I specify its code as "BOARD_MANSION_OF_MADNESS"
        And I name it "Mansion of Madness" in "English (United States)"
        And I set its price to "$100.00" for "United States" channel
        And I add it
        Then the slug of the "Mansion of Madness" product should be "mansion-of-madness"

    @ui @javascript @api
    Scenario: Creating a product with a custom slug
        When I want to create a new simple product
        And I specify its code as "BOARD_MANSION_OF_MADNESS"
        And I name it "Mansion of Madness" in "English (United States)"
        And I set its price to "$100.00" for "United States" channel
        And I set its slug to "mom-board-game" in "English (United States)"
        And I add it
        Then the slug of the "Mansion of Madness" product should be "mom-board-game"

    @ui
    Scenario: Seeing disabled slug field when editing a product
        Given the store has a product "Mansion of Madness"
        When I want to modify this product
        Then the slug field should not be editable

    @ui @javascript @api
    Scenario: Prevent from editing a slug while changing a product name
        Given the store has a product "Mansion of Madness"
        When I want to modify this product
        And I rename it to "Mansion of Madness: Second Edition" in "English (United States)"
        And I save my changes
        Then the slug of the "Mansion of Madness: Second Edition" product should still be "mansion-of-madness"

    @ui @javascript @api
    Scenario: Automatically changing a product's slug while editing a product's name
        Given the store has a product "Mansion of Madness"
        When I want to modify this product
        And I enable slug modification
        And I rename it to "Small World" in "English (United States)"
        And I save my changes
        Then the slug of the "Small World" product should be "small-world"

    @ui @javascript @api
    Scenario: Manually changing a product's slug while editing a product's name
        Given the store has a product "Mansion of Madness"
        When I want to modify this product
        And I enable slug modification
        And I rename it to "Small World" in "English (United States)"
        And I set its slug to "small-world-board-game" in "English (United States)"
        And I save my changes
        Then the slug of the "Small World" product should be "small-world-board-game"
