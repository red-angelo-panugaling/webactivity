const actionDropdowns = [...document.querySelectorAll('.user-actions-dropdown')].map((dropdown) => ({
    dropdown,
    toggle: dropdown.querySelector('[data-action-menu-toggle]'),
    menu: dropdown.querySelector('.user-actions-menu'),
}));

const closeActionDropdown = ({ dropdown, toggle, menu }) => {
    menu.classList.remove('show');
    menu.removeAttribute('style');
    dropdown.append(menu);
    toggle.setAttribute('aria-expanded', 'false');
};

const openActionDropdown = (actionDropdown) => {
    const { toggle, menu } = actionDropdown;

    menu.classList.add('show');
    document.body.append(menu);
    menu.style.position = 'fixed';
    menu.style.zIndex = '1090';

    const toggleBounds = toggle.getBoundingClientRect();
    const menuBounds = menu.getBoundingClientRect();
    const left = Math.max(8, Math.min(
        toggleBounds.right - menuBounds.width,
        window.innerWidth - menuBounds.width - 8,
    ));
    const top = toggleBounds.bottom + menuBounds.height > window.innerHeight
        ? toggleBounds.top - menuBounds.height
        : toggleBounds.bottom;

    menu.style.left = `${left}px`;
    menu.style.top = `${Math.max(8, top)}px`;
    toggle.setAttribute('aria-expanded', 'true');
};

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-action-menu-toggle]');
    const openDropdown = actionDropdowns.find(({ toggle }) => toggle.getAttribute('aria-expanded') === 'true');

    if (toggle) {
        event.preventDefault();

        if (openDropdown?.toggle === toggle) {
            closeActionDropdown(openDropdown);

            return;
        }

        actionDropdowns.forEach(closeActionDropdown);
        openActionDropdown(actionDropdowns.find((actionDropdown) => actionDropdown.toggle === toggle));

        return;
    }

    if (openDropdown) {
        closeActionDropdown(openDropdown);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    const openDropdown = actionDropdowns.find(({ toggle }) => toggle.getAttribute('aria-expanded') === 'true');

    if (openDropdown) {
        closeActionDropdown(openDropdown);
        openDropdown.toggle.focus();
    }
});

window.addEventListener('scroll', () => actionDropdowns.forEach((actionDropdown) => {
    if (actionDropdown.toggle.getAttribute('aria-expanded') === 'true') {
        closeActionDropdown(actionDropdown);
    }
}), true);

window.addEventListener('resize', () => actionDropdowns.forEach((actionDropdown) => {
    if (actionDropdown.toggle.getAttribute('aria-expanded') === 'true') {
        closeActionDropdown(actionDropdown);
    }
}));
