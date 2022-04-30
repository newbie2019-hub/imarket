export const rules = {
 data: () => ({
  emailRule: [
   v => !!v || 'E-mail is required',
   v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
  ],
  required: [(v) => !!v || 'Field is required'],
  gender: [
   {
    text: 'Male',
    value: 'Male',
   },
   {
    text: 'Female',
    value: 'Female',
   },
  ],
 })
}