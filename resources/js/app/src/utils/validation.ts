import { defineRule } from 'vee-validate';
import {email,required} from "@vee-validate/rules"
defineRule('required', required);
defineRule('email', email);
