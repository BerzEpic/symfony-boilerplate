<template>
  <div>
    <h1>{{ $t('common.nav.items') }}</h1>
    <b-card>
      <b-form inline @submit.stop.prevent>
        <label class="sr-only" for="inline-form-input-search">{{
          $t('common.search')
        }}</label>
        <b-input
          id="inline-form-input-search"
          v-model="filters.search"
          class="mb-2 mr-sm-2 mb-sm-0"
          style="min-width: 50%"
          type="search"
          :placeholder="$t('common.search')"
          autofocus
          trim
          :debounce="debounce"
          @update="onSearch"
        ></b-input>
        <div class="m-auto mr-sm-0 ml-sm-auto">
          <b-button
            variant="primary"
            :to="localePath({ name: 'items-create' })"
            >{{ $t('common.create') }}</b-button
          >
        </div>
      </b-form>
    </b-card>
    <b-card class="mt-3">
      <b-table
        hover
        :responsive="true"
        :no-local-sorting="true"
        :sort-by="boostrapTableSortBy"
        :sort-desc="isDesc"
        :items="items"
        :fields="fields"
        :busy="isLoading"
        @sort-changed="onSort"
      >
        <template #cell(actions)="data">
          <b-button
            size="sm"
            variant="primary"
            :aria-label="$t('common.edit')"
            :to="
              localePath({
                name: 'items-id',
                params: { id: data.item.id },
              })
            "
          >
            <b-icon icon="pencil"></b-icon>
          </b-button>
        </template>
        <template #cell(categories)="data">
          <b-badge
            v-for="(category, index) in data.item.categories"
            :key="index"
            pill
            variant="info"
            >{{ category.label }}</b-badge
          >
        </template>
        <template #table-busy>
          <div class="text-center my-2">
            <b-spinner class="align-middle" variant="primary"></b-spinner>
          </div>
        </template>
      </b-table>
      <b-pagination
        v-model="currentPage"
        :per-page="itemsPerPage"
        :total-rows="count"
        align="center"
        @change="onPaginate"
        @click.native="$scrollToTop"
      />
    </b-card>
  </div>
</template>

<script>
import { Form } from '@/mixins/form'
import { List, defaultItemsPerPage, calculateOffset } from '@/mixins/list'
import { Roles } from '@/mixins/roles'
import { ItemsQuery } from '@/graphql/items/items.query'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { GenericToast } from '@/mixins/generic-toast'

export default {
  name: 'ItemList',
  mixins: [Form, List, Roles, GlobalOverlay, GenericToast],
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(ItemsQuery, {
        search: context.route.query.search || '',
        limit: defaultItemsPerPage,
        offset: calculateOffset(
          context.route.query.page || 1,
          defaultItemsPerPage
        ),
      })
      return {
        items: result.items.items,
        count: result.items.count,
      }
    } catch (e) {
      context.error(e)
    }
  },
  data() {
    return {
      filters: {
        search: this.$route.query.search || '',
      },
      fields: [
        {
          key: 'label',
          label: 'label',
          sortable: true,
        },
        {
          key: 'categories',
          label: 'categories',
          sortable: false,
        },
        {
          key: 'actions',
          label: this.$t('common.list.actions'),
          sortable: false,
        },
      ],
      sortByMap: {},
    }
  },
  methods: {
    async doSearch() {
      this.isLoading = true
      this.updateRouter()
      try {
        const result = await this.$graphql.request(ItemsQuery, {
          search: this.filters.search,
          limit: this.itemsPerPage,
          offset: this.offset,
        })
        this.items = result.items.items
        this.count = result.items.count
        this.isLoading = false
      } catch (e) {
        this.$nuxt.error(e)
      }
    },
  },
}
</script>
