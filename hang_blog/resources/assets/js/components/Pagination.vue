<template>
<ul class="pagination">
  <li class="pagination-item">
    <a href="#"
    @click="onClickFirstPage"
    :disabled="isInFirstPage"
    :class="{disable: isInFirstPage, cursorHover: !isInFirstPage}"
    >
    <<
    </a>
  </li>

  <li class="pagination-item">
    <a href="#"
    @click="onClickPreviousPage"
    :disabled="isInFirstPage"
    :class="{disable: isInFirstPage, cursorHover: !isInFirstPage}"
    >
    <
    </a>
  </li>

  <li
  v-for="page in pages"
  class="pagination-item"
  >
    <a href="#"
    @click="onClickPage(page.name)"
    :disabled="page.isDisabled"
    :class="{ active: isPageActive(page.name), cursorHover: !isPageActive(page.name) }"
    >
    {{ page.name }}
    </a>
  </li>

  <li class="pagination-item">
    <a href="#"
    @click="onClickNextPage"
    :disabled="isInLastPage"
    :class="{disable: isInLastPage, cursorHover: !isInLastPage}"
    >
    >
    </a>
  </li>

  <li class="pagination-item">
    <a href="#"
    @click="onClickLastPage"
    :disabled="isInLastPage"
    :class="{disable: isInLastPage, cursorHover: !isInLastPage}"
    >
    >>
    </a>
  </li>
</ul>
</template>

<script>
export default {
props: {
  maxVisibleButtons: {
    type: Number,
    required: false
  },
  totalPages: {
    type: Number,
    required: false
  },
  totalItems: {
    type: Number,
    required: false
  },
  currentPage: {
    type: Number,
    required: true
  },
  itemsPerPage: {
    type: Number,
    required: false
  },
//visiblePages is an optional property which defines how many pages should be displayed in the pagination component
  visiblePages: {
    type: Number,
    required: false,
    default: 5,
    coerce: (val) => parseInt(val)
    }
  },

data() {
  return {};
},

computed: {
  totalCalculate() {
    let total = 0;
    if (this.totalPages) {
      total = this.totalPages;
    } else {
      if (this.totalItems % this.itemsPerPage === 0) {
        total = this.totalItems / this.itemsPerPage;
      } else {
        total = Math.floor(this.totalItems/this.itemsPerPage) + 1;
      }
    }

    return total;
  },

  maxVisibleButtonsCalculate() {
    if (this.maxVisibleButtons) {
      return this.maxVisibleButtons
    } else if (this.totalCalculate >= 4) {
      return 4
    } else {
      return this.totalCalculate
    }
  },

  lastPage() {
    if (this.totalPages) {
      return this.totalPages;
    } else {
      return this.totalItems % this.itemsPerPage === 0
      ? this.totalItems / this.itemsPerPage
      : Math.floor(this.totalItems / this.itemsPerPage) + 1
    }
  },

  startPage() {
  // When on the first page
  if (this.currentPage === 1) {
    return 1;
  }
  // When on the last page
  if (this.currentPage === this.totalCalculate ||
  (this.currentPage <= this.totalCalculate
  && this.currentPage >= this.totalCalculate - this.maxVisibleButtonsCalculate + 1)) {
    return this.totalCalculate - this.maxVisibleButtonsCalculate + 1;
  }

  // When in between
    return this.currentPage - 1;
  },

  pages() {
    const range = [];
    for (let i = this.startPage;
    i <= Math.min(this.startPage + this.maxVisibleButtonsCalculate - 1, this.totalCalculate);
    i+= 1 ) {
      range.push({
        name: i,
        isDisabled: i === this.currentPage
      });
    }
    return range;
  },

  isInFirstPage() {
    return this.currentPage === 1;
  },

  isInLastPage() {
    if (this.totalPages && this.currentPage === this.totalPages) {
      return true;
    } else {
      if (this.totalItems % this.itemsPerPage === 0) {
        return this.currentPage === this.totalItems/this.itemsPerPage;
      } else {
        return this.currentPage === Math.floor(this.totalItems/this.itemsPerPage + 1);
      }
    }

      return false;
    },
  },

  methods: {
    onClickFirstPage() {
      this.$emit('pagechanged', 1);
    },
    onClickPreviousPage() {
      this.$emit('pagechanged', this.currentPage - 1 <= 0 ? 1 : this.currentPage - 1);
    },
    onClickPage(page) {
      this.$emit('pagechanged', page);
    },
    onClickNextPage() {
      this.$emit('pagechanged', this.currentPage + 1 > this.totalCalculate ? this.totalCalculate : this.currentPage + 1);
    },
    onClickLastPage() {
      this.$emit('pagechanged', this.totalCalculate);
    },
    isPageActive(page) {
      return this.currentPage === page;
    }
  }
}
</script>

<style scoped>
.pagination {
  list-style-type: none;
  padding-left: 0;
}

.pagination>li:last-child>a {
  border-top-right-radius: .25rem;
  border-bottom-right-radius: .25rem;
}

.pagination > li:first-child > a {
  border-top-left-radius: .25rem;
  border-bottom-left-radius: .25rem;
}

a {
  text-decoration: none;
  color: #0275d8;
  border: 1px solid #ddd;
  background-color: #fff;
  margin-left: -1px;
  padding: .5rem .75rem;
  float: left;
  position: relative;
}

a.disable {
  color: #818a91;
  cursor: not-allowed;
  background-color: #fff;
  border-color: #ddd;
}

a.cursorHover:hover {
  background-color: #ddd;
}

.pagination-item {
  display: inline-block;
}

.active {
  background-color: #4AAE9B;
  color: #ffffff;
}
</style>