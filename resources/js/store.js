// store.js
import { createStore } from 'vuex';

export default createStore({
    state: {
        groups: []
    },
    mutations: {
        addGroup(state, group) {
            state.groups.push(group);
        }
    },
    actions: {
        createGroup({ commit }, group) {
            commit('addGroup', group);
        }
    }
});
