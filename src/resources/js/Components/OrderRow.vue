<template>
  <tr>
    <td class="text-center">{{ order.id }}</td>
    <td>{{ order.updated_at }}</td>
    <td>{{ order.name }}</td>
    <td class="text-center">
      <button class="btn_small-blue-3" @click="$emit('download', order.path)">ダウンロード</button>
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    order: {
      type: Object,
      required: true,
    },
  },
};
</script>
