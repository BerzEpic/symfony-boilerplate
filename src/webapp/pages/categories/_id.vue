<template>
  <div>
    <h1>{{ $t('common.category') }} {{ $t('common.edit') }}</h1>
    <b-card>
      <b-form @submit.stop.prevent="onSubmit">
        <b-form-row>
          <b-col md="6">
            <b-form-group
              id="input-group-label"
              label="Label"
              label-for="input-label"
            >
              <b-form-input
                id="input-label"
                v-model="form.label"
                type="text"
                placeholder="label"
                autofocus
                trim
                required
                :state="formState('label')"
              />
              <b-form-invalid-feedback :state="formState('label')">
                <ErrorsList :errors="formErrors('label')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-button type="submit" variant="primary">
          {{ $t('common.update') }}
        </b-button>
        <b-button
          size="sm"
          variant="danger"
          :aria-label="$t('common.delete')"
          @click="onDelete()"
        >
          <b-icon icon="trash"></b-icon>
        </b-button>
      </b-form>
    </b-card>
  </div>
</template>

<script>
import { CategoryQuery } from '@/graphql/categories/category.query'
import { UpdateCategoryMutation } from '@/graphql/categories/update_category.mutation'
import { Form } from '@/mixins/form'
import { Roles } from '@/mixins/roles'
import { Locales } from '@/mixins/locales'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Auth } from '@/mixins/auth'
import { Images } from '@/mixins/images'
import { GenericToast } from '@/mixins/generic-toast'
import ErrorsList from '@/components/forms/ErrorsList'
import { DeleteCategoryMutation } from '@/graphql/categories/delete_category.mutation'

export default {
  name: 'CategoryEdit',
  components: { ErrorsList },
  mixins: [Form, Roles, Locales, GlobalOverlay, Auth, Images, GenericToast],
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(CategoryQuery, {
        id: context.params.id,
      })

      return {
        form: {
          label: result.category.label,
        },
      }
    } catch (e) {
      context.error(e)
    }
  },
  data() {
    return {
      form: {
        label: '',
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(UpdateCategoryMutation, {
          id: this.$route.params.id,
          label: this.form.label,
        })

        this.genericSuccessToast()
        this.$router.push(this.localePath({ name: 'categories' }))
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
    async onDelete(id) {
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(DeleteCategoryMutation, {
          id: this.$route.params.id,
        })

        this.genericSuccessToast()
        this.$router.push(this.localePath({ name: 'categories' }))
      } catch (e) {
        this.$nuxt.error(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
