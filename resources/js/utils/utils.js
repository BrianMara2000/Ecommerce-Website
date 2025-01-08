import { router } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

export function useCart() {
  function addToCart(product, quantity) {
    router.visit(route("cart.add", product), {
      method: "post",
      data: { quantity },
      onSuccess: (page) => {
        if (page.props.flash.success) {
          toast.success(page.props.flash.success);
        }
      },
    });
  }

  return {
    addToCart,
  };
}
export function useFormatDate(isoDate) {
  const date = new Date(isoDate);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleString(undefined, options); // Adjusts to the user's locale
}

export function useCapitalizedStatus(status) {
  return status.charAt(0).toUpperCase() + status.slice(1);
}
