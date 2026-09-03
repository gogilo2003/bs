declare module 'ziggy-js' {
    export const ZiggyVue: {
        install(app: any, options?: any): void;
    };
    export function route(name?: string, params?: any, absolute?: boolean, config?: any): any;
    export function useRoute(defaultConfig?: any): typeof route;
}
