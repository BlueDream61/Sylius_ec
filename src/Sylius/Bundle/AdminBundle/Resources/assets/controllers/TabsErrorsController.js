/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['tab'];
  observer;

  initialize() {
    this.observer = new MutationObserver(() => {
      this.findErrors();
    });
  }

  connect() {
    this.findErrors();
    this.observe();
  }

  findErrors() {
    this.tabTargets.forEach((tab) => {
      const tabContentId = tab.attributes['data-bs-target'].value;

      this.tabHasErrors(this.element.querySelector(tabContentId)) ? this.appendIndicator(tab) : this.removeIndicator(tab);
    });
  }

  tabHasErrors(target) {
    return target.querySelectorAll('.is-invalid').length > 0;
  }

  appendIndicator(target) {
    const errorElement = document.createElement('div');
    const errorContainer = target.querySelector('.tab-icons');

    errorElement.classList.add('tab-error');
    errorElement.innerText = '!';

    if (!errorContainer.querySelector('.tab-error')) {
      errorContainer.insertBefore(errorElement, errorContainer.firstChild);
    }
  }

  removeIndicator(tab) {
    const errorBadge = tab.querySelector('.tab-error');

    if (errorBadge) {
      errorBadge.remove();
    }
  }

  observe() {
    this.observer.observe(this.element.querySelector('#product_attribute_tabs'), { attributes: false, childList: true, subtree: false });
  }
}
