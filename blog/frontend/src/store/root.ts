import { GetterTree, ActionContext, ActionTree, MutationTree } from 'vuex'
import { RootState } from 'store'
import * as BackendRoutes from './modules/BackendRoutes'

export const types = {}

export interface State {}

export const state = (): State => ({ // было State
  [BackendRoutes.NAME]: BackendRoutes.state
})

export const getters: GetterTree<State, RootState> = {}

export interface Action<S, R> extends ActionTree<S, R> {
  nuxtServerInit(context: ActionContext<S, R>, serverContext): void
}

export const actions: Action<State, RootState> = {
  async nuxtServerInit(context, serverContext) {
    await getBackandRoutes(context, serverContext)
  }
}

export const mutations: MutationTree<State> = {}

async function getBackandRoutes ({ commit, state }, { app }) {
  let backendRoutes: BackendRoutes.Route[] = [] //  | null

  if (app.$cookies.get('backendRoutes')) {
    backendRoutes = app.$cookies.get('backendRoutes')
  } else {
    const routes: {} = await app.$get('routes/') // { [s: string]: string; }
    // backendRoutes = []

    // трансформирует в массив роутов нужного типа
    for (let name in routes) {
      backendRoutes.push({
        name,
        path: routes[name]
      })
    }

    app.$cookies.set('backendRoutes', backendRoutes)
  }

  commit(
    `${BackendRoutes.NAME}/${BackendRoutes.TYPES.SET}`,
    backendRoutes,
    { root: true }
  )
}
