export function useCapitalizedStatus(status) {
  return status.charAt(0).toUpperCase() + status.slice(1);
}
