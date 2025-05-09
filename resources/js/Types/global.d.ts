import type { LoDashStatic } from "lodash";

export {};
declare global {
    declare type TPageLinks = { active: boolean; label: string; url?: string };

    declare type TPageProps<T = any> = {
        current_page: number;
        data: T[];
        first_page_url: string;
        from: any;
        last_page: number;
        last_page_url: string;
        links: TPageLinks[];
        next_page_url: string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: any;
        total: number;
    };

    interface ISortTable {
        column: string;
        direction: "asc" | "desc";
    }
}
