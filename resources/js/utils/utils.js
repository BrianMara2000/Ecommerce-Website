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
