<template>
  <tr>
    <td class="text-center">{{ row.id }}</td>
    <td>{{ row.updated_at }}</td>
    <td>{{ row.name }}</td>
    <td class="text-center">
      <button class="btn_small-blue-3" @click="$emit('download', row.path)">ダウンロード</button>
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    row: {
      type: Object,
      required: true,
    },
  },
};
</script>
