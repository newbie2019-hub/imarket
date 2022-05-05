export const rules = {
 data: () => ({
  emailRule: [
   v => !!v || 'E-mail is required',
   v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
  ],
  required: [(v) => !!v || 'Field is required'],
  minChar: [(v) => v.length >= 11 || 'Input should be atleast 11 characters'],
  isLoading: false
 })
}

