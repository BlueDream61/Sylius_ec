@managing_taxons
Feature: Editing taxon's slug
    In order to In order to manage access path to taxon page
    As an Administrator
    I want to be able to edit taxon's slug
    
    Background:
        Given the store is available in "English (United States)"
        And I am logged in as an administrator

    @ui @javascript @todo
    Scenario: Creating a root taxon with an autogenerated slug
        Given I want to create a new taxon
        When I specify its code as "MEDIEVAL_WEAPONS"
        And I name it "Medieval weapons" in "English (United States)"
        And I add it
        Then this taxon slug should be "medieval-weapons"

    @ui @javascript @todo
    Scenario: Creating a root taxon with a custom slug
        Given I want to create a new taxon
        When I specify its code as "MEDIEVAL_WEAPONS"
        And I name it "Medieval weapons" in "English (United States)"
        And I set its slug to "mw"
        And I add it
        Then this taxon slug should be "mw"

    @ui @javascript @todo
    Scenario: Creating a taxon with an autogenerated slug for parent
        Given the store has "Medieval weapons" taxonomy
        And I want to create a new taxon for "Medieval weapons"
        When I specify its code as "SIEGE_ENGINES"
        And I name it "Siege engines" in "English (United States)"
        And I add it
        Then this taxon slug should be "medieval-weapons/siege-engines"

    @ui @javascript @todo
    Scenario: Creating a taxon with a custom slug for parent
        Given the store has "Medieval weapons" taxonomy
        And I want to create a new taxon for "Medieval weapons"
        When I specify its code as "SIEGE_ENGINES"
        And I name it "Siege engines" in "English (United States)"
        And I set its slug to "medieval-weapons/siege"
        And I add it
        Then this taxon slug should be "medieval-weapons/siege"

    @ui @todo
    Scenario: Seeing disabled slug field when editing a taxon
        Given the store has "Medieval weapons" taxonomy
        When I want to modify this taxon
        Then the slug field should not be editable

    @ui @javascript @todo
    Scenario: Prevent from editing a slug while changing a taxon name
        Given the store has "Medieval weapons" taxonomy
        When I want to modify this taxon
        And I rename it to "Renaissance weapons" in "English (United States)"
        And I save my changes
        Then the slug of the "Renaissance weapons" taxon should still be "medieval-weapons"

    @ui @javascript @todo
    Scenario: Automatically changing a taxon's slug while editing a taxon's name
        Given the store has "Medieval weapons" taxonomy
        When I want to modify this taxon
        And I enable slug modification
        And I rename it to "Renaissance weapons" in "English (United States)"
        And I save my changes
        Then the slug of the "Renaissance weapons" taxon should be "renaissance-weapons"

    @ui @javascript @todo
    Scenario: Manually changing a taxon's slug while editing a taxon's name
        Given the store has "Medieval weapons" taxonomy
        When I want to modify this taxon
        And I enable slug modification
        And I rename it to "Renaissance weapons" in "English (United States)"
        And I set its slug to "renaissance"
        And I save my changes
        Then the slug of the "Renaissance weapons" product should be "renaissance"


