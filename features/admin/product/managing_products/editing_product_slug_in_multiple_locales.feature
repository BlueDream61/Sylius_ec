@managing_products
Feature: Editing product's slug in multiple locales
    In order to manage access path to product page in many languages
    As an Administrator
    I want to be able to edit product's slug in multiple locales

    Background:
        Given the store operates on a single channel in "United States"
        And the store is available in "English (United States)"
        And the store is also available in "Polish (Poland)"
        And I am logged in as an administrator

    @ui @api
    Scenario: Creating a product with custom slugs
        When I want to create a new configurable product
        And I specify its code as "PUG_PUGGINTON_PLUSHIE"
        And I name it "Pug Pugginton Plushie" in "English (United States)" locale
        And I set its slug to "sir-pugginton" in "English (United States)" locale
        And I name it "Pluszak Mops Mopsiński" in "Polish (Poland)" locale
        And I set its slug to "pan-mopsinski" in "Polish (Poland)" locale
        And I add it
        Then the slug of the "Pug Pugginton Plushie" product should be "sir-pugginton" in the "English (United States)" locale
        And the slug of the "Pug Pugginton Plushie" product should be "pan-mopsinski" in the "Polish (Poland)" locale

    @ui @mink:chromedriver @api
    Scenario: Creating a product with autogenerated slugs
        When I want to create a new configurable product
        And I specify its code as "PUG_PUGGINTON_PLUSHIE"
        And I name it "Pug Pugginton Plushie" in "English (United States)" locale
        And I generate its slug in "English (United States)" locale
        And I name it "Pluszak Mops Mopsiński" in "Polish (Poland)" locale
        And I generate its slug in "Polish (Poland)" locale
        And I add it
        Then the slug of the "Pug Pugginton Plushie" product should be "pug-pugginton-plushie" in the "English (United States)" locale
        And the slug of the "Pug Pugginton Plushie" product should be "pluszak-mops-mopsinski" in the "Polish (Poland)" locale
