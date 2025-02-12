export function useCapitalizedFirstLetter(status) {
  return status.charAt(0).toUpperCase() + status.slice(1);
}
