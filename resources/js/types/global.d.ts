import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { PageProps as AppPageProps } from './';

type ZiggyRoute = {
    (): {
        current(routeName?: string, params?: Record<string, any>): boolean;
    };
    (name: string, params?: Record<string, any>, absolute?: boolean): string;
    current(routeName?: string, params?: Record<string, any>): boolean;
};

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    /* eslint-disable no-var */
    var route: ZiggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: ZiggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps { }
}
