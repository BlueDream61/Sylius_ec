/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import 'semantic-ui-css/components/accordion';
import $ from 'jquery';
import 'jquery.dirtyforms/jquery.dirtyforms';

import 'sylius/ui/app';
import 'sylius/ui/sylius-auto-complete';
import 'sylius/ui/sylius-product-attributes';
import 'sylius/ui/sylius-product-auto-complete';
import 'sylius/ui/sylius-prototype-handler';

import './sylius-compound-form-errors';
import './sylius-lazy-choice-tree';
import './sylius-menu-search';
import './sylius-move-product-variant';
import './sylius-move-taxon';
import './sylius-notification';
import './sylius-product-images-preview';
import './sylius-product-slug';
import './sylius-taxon-slug';

import StatisticsComponent from './sylius-statistics';
import SyliusTaxonomyTree from './sylius-taxon-tree';
import formsList from './sylius-forms-list';

$(document).ready(() => {
  $('#sylius_product_variant_pricingCalculator').handlePrototypes({
    prototypePrefix: 'sylius_product_variant_pricingCalculator',
    containerSelector: '#sylius_calculator_container',
  });

  $('#sylius_customer_createUser').change(() => {
    $('#user-form').toggle();
  });

  $('.sylius-autocomplete').autoComplete();

  $('.product-select.ui.fluid.multiple.search.selection.dropdown').productAutoComplete();
  $('div#attributeChoice > .ui.dropdown.search').productAttributes();

  $('table thead th.sortable').on('click', (event) => {
    window.location = $(event.currentTarget).find('a').attr('href');
  });

  $('.sylius-update-product-variants').moveProductVariant($('.sylius-product-variant-position'));
  $('.sylius-taxon-move-up').taxonMoveUp();
  $('.sylius-taxon-move-down').taxonMoveDown();

  $('#sylius_shipping_method_calculator').handlePrototypes({
    prototypePrefix: 'sylius_shipping_method_calculator_calculators',
    containerSelector: '.configuration',
  });

  $('#actions a[data-form-collection="add"]').on('click', () => {
    setTimeout(() => {
      $('select[name^="sylius_promotion[actions]"][name$="[type]"]').last().change();
    }, 50);
  });
  $('#scopes a[data-form-collection="add"]').on('click', (event) => {
    const name = $(event.target).closest('form').attr('name');

    setTimeout(() => {
      $(`select[name^="${name}[scopes]"][name$="[type]"]`).last().change();
    }, 50);
  });

  $(document).on('collection-form-add', () => {
    $('.sylius-autocomplete').each((index, element) => {
      if ($._data($(element).get(0), 'events') == undefined) {
        $(element).autoComplete();
      }
    });
  });
  $(document).on('collection-form-update', () => {
    $('.sylius-autocomplete').each((index, element) => {
      if ($._data($(element).get(0), 'events') == undefined) {
        $(element).autoComplete();
      }
    });
  });

  document.querySelector('#sylius_catalog_promotion_scopes_0_type').onchange = function () {
    const parent = this.parentElement;
    const newConfig = document.createElement('div');
    newConfig.innerHTML = this.selectedOptions[0].getAttribute('data-configuration');
    const oldConfig = parent.nextElementSibling;

    parent.parentElement.replaceChild(newConfig, oldConfig);

    let oldConfigInputName = oldConfig.querySelector('input').getAttribute('name');
    let newConfigInputName = newConfig.querySelector('input').getAttribute('name');

    newConfigInputName = oldConfigInputName.replace(
        oldConfigInputName.substring(oldConfigInputName.lastIndexOf("[") + 1, oldConfigInputName.lastIndexOf("]")),
        newConfigInputName.substring(newConfigInputName.indexOf("[") + 1, newConfigInputName.lastIndexOf("]"))
    );

    $(newConfig).find('input').attr('name', newConfigInputName);
    $(newConfig).find('.sylius-autocomplete').autoComplete();
  };

  $('.sylius-tabular-form').addTabErrors();
  $('.ui.accordion').addAccordionErrors();
  $('#sylius-product-taxonomy-tree').choiceTree('productTaxon', true, 1);

  $(document).notification();
  $(document).productSlugGenerator();
  $(document).taxonSlugGenerator();
  $(document).previewUploadedImage('#sylius_product_images');
  $(document).previewUploadedImage('#sylius_taxon_images');

  $(document).previewUploadedImage('#add-avatar');

  $('body').on('DOMNodeInserted', '[data-form-collection="item"]', (event) => {
    if ($(event.target).find('.ui.accordion').length > 0) {
      $(event.target).find('.ui.accordion').accordion();
    }
  });

  const taxonomyTree = new SyliusTaxonomyTree();

  $(`${formsList}, .check-unsaved`).dirtyForms();

  $('#more-details').accordion({ exclusive: false });

  $('.variants-accordion__title').on('click', '.icon.button', function(e) {
    $(e.delegateTarget).next('.variants-accordion__content').toggle();
    $(this).find('.dropdown.icon').toggleClass('counterclockwise rotated');
  });

  const dashboardStatistics = new StatisticsComponent(document.querySelector('.stats'));

  $('.sylius-admin-menu').searchable('.sylius-admin-menu-search-input');
});

window.$ = $;
window.jQuery = $;
