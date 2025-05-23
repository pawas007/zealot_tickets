import {defineRule, configure} from 'vee-validate';

import {email, required, max, min} from "@vee-validate/rules"
import en from '@vee-validate/i18n/dist/locale/en.json'
import {localize} from '@vee-validate/i18n';

defineRule('required', required);
defineRule('email', email);
defineRule('max', max);
defineRule('min', min);


configure({
    generateMessage: localize({
        en: {
            ...en,
            names: {
                user_id: 'Assign To',
            },
        },
    }),
});




