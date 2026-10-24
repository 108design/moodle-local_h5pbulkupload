/**
 * This file is part of a 108design source-available software product.
 *
 * Use and modification are permitted only under the Software License included
 * with this distribution. See LICENSE.md for the full terms.
 *
 * File documentation for amd/src/admin_help_form.js.
 *
 * @package   local_h5pbulkupload
 * @copyright 2026 Andreas Giesen
 * @license   See LICENSE.md for the full terms.
 */
// Copyright (C) 2026 Andreas Giesen. See LICENSE.md.

/** Align help in our registered forms without changing fields or their values. */
export const init = () => {
    const navigation = document.querySelector('#usernavigation');
    const alignNavbarHelp = () => {
        if (!navigation) return;
        const help = navigation.querySelector('.local-h5pbulkupload-help-navbar');
        const children = [...navigation.children];
        const userMenu = children.find(node => node.matches('[data-region="usermenu"], .usermenu-container'));
        const userIndex = userMenu ? children.indexOf(userMenu) : children.length;
        // Older Moodle places its divider after the user menu, outside the help insertion area.
        const anchor = children.find((node, index) => index < userIndex &&
            node.classList.contains('divider')) || userMenu;
        if (help && anchor && (help.parentElement !== navigation || help.nextElementSibling !== anchor)) {
            navigation.insertBefore(help, anchor);
        }
    };
    // Navbar callbacks can finish in any order; keep help immediately before the user-area divider.
    alignNavbarHelp();
    if (navigation) new MutationObserver(alignNavbarHelp).observe(navigation, {childList: true});

    const form = document.querySelector('form.mform');
    const placeHelp = () => {
        form?.querySelectorAll('[data-h5p-help-field]').forEach(help => {
            if (help.dataset.placed) return;
            const field = help.dataset.h5pHelpField;
            const item = document.getElementById('fitem_id_' + field);
            const control = field === 'courseid' ? item?.querySelector('.form-autocomplete-input') :
                field === 'packages' ? item?.querySelector('.filemanager') :
                    document.getElementById('id_' + field)?.closest('.form-check');
            if (!control) return;
            if (field === 'skipduplicates') {
                control.append(help);
            } else {
                const row = document.createElement('div');
                row.className = 'local-h5pbulkupload-control-help';
                control.before(row);
                row.append(control, help);
            }
            help.dataset.placed = '1';
        });
        form?.querySelectorAll('.fitem').forEach(item => {
            const label = item.querySelector('.col-form-label');
            if (label) label.classList.add('local-h5pbulkupload-form-help-label');
        });
    };
    placeHelp();
    if (form) new MutationObserver(placeHelp).observe(form, {childList: true, subtree: true});
};
