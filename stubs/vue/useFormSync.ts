import { reactive } from "vue";

export function useFormSync(schema: any) {
  const state = reactive({
    values: {} as Record<string, any>,
    errors: {} as Record<string, string>,
  });

  const register = (name: string) => ({
    name,
    value: state.values[name] || "",
    onInput: (e: Event) => {
      const target = e.target as HTMLInputElement;
      state.values[name] = target.value;
    },
  });

  const validate = (): boolean => {
    const newErrors: Record<string, string> = {};

    Object.entries(schema.fields).forEach(([field, rules]) => {
      const value = state.values[field];

      if (rules.required && !value) {
        newErrors[field] = schema.messages[`${field}.required`] || `${field} is required`;
      }
      if (rules.email && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        newErrors[field] = schema.messages[`${field}.email`] || `${field} must be a valid email`;
      }
    });

    state.errors = newErrors;
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (callback: (data: Record<string, any>) => void) => (e: Event) => {
    e.preventDefault();
    if (validate()) callback(state.values);
  };

  return { register, handleSubmit, state };
}
