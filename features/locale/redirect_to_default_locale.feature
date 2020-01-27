@locales
Feature: Redirect to default locale
    In order to prevent locale deactivation
    As a Customer
    I want to be redirect with default locale if locale is not available

    Background:
        Given the store operates on a channel named "Web"
        And that channel allows to shop using "English (United States)" and "French (France)" locales
        And it uses the "English (United States)" locale by default

    @ui
    Scenario: Do not Redirect if locale is available
        When I browse that channel
        And I show homepage with the locale "French (France)"
        Then I should shop using the "français (France)" locale

    @ui
    Scenario: Redirect to default locale
        When I browse that channel
        And I show homepage with the locale "Polish (Poland)"
        Then I should shop using the "English (United States)" locale
