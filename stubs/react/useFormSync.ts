import { useState } from "react";

type Schema = {
  fields: Record<string, Record<string, any>>;
  messages: Record<string, string>;
};

export function useFormSync(schema: Schema) {
  const [values, setValues] = useState<Record<string, any>>({});
  const [errors, setErrors] = useState<Record<string, string>>({});

  const register = (name: string) => ({
    name,
    value: values[name] || "",
    onChange: (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
      setValues({ ...values, [name]: e.target.value });
    },
  });

  const validate = (): boolean => {
    const newErrors: Record<string, string> = {};

    Object.entries(schema.fields).forEach(([field, rules]) => {
      const value = values[field];

      if (rules.required && !value) {
        newErrors[field] = schema.messages[`${field}.required`] || `${field} is required`;
      }

      if (rules.email && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        newErrors[field] = schema.messages[`${field}.email`] || `${field} must be a valid email`;
      }

      if (rules.min && value && value.length < Number(rules.min)) {
        newErrors[field] = schema.messages[`${field}.min`] || `${field} must be at least ${rules.min} characters`;
      }

      if (rules.max && value && value.length > Number(rules.max)) {
        newErrors[field] = schema.messages[`${field}.max`] || `${field} must not exceed ${rules.max} characters`;
      }
    });

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (callback: (data: Record<string, any>) => void) => (e: React.FormEvent) => {
    e.preventDefault();
    if (validate()) callback(values);
  };

  return { register, handleSubmit, errors, values, setValues };
}
