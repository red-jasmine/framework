/*
 * @Author: liushoukun liushoukun66@gmail.com
 * @Date: 2023-12-25 23:08:04
 * @LastEditors: liushoukun liushoukun66@gmail.com
 * @LastEditTime: 2025-01-08 16:41:33
 * @FilePath: \docs\.vitepress\config.mts
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import {defineConfig} from 'vitepress'
import markdownItTextualUml from 'markdown-it-textual-uml';



import { withSidebar } from 'vitepress-sidebar';

const vitePressSidebarOptions = {
    // VitePress Sidebar's options here...
    documentRootPath: '/docs',
    collapsed: false,
    capitalizeFirst: true,
    useTitleFromFileHeading:true,
    useTitleFromFrontmatter:true,
    useFolderTitleFromIndexFile:true,
    useFolderLinkFromIndexFile:true,
    sortMenusByFrontmatterOrder:true,
};

// https://vitepress.dev/reference/site-config
export default defineConfig(withSidebar({
    srcDir: './docs',
    title: "RedJasmine Docs",
    description: "A VitePress Site",
    base: '/docs/',
    appearance: "dark",
    themeConfig: {
        outline:{
            label: '本页面'
        },
        // https://vitepress.dev/reference/default-theme-config
        nav: [
            {text: '首页', link: '/'},
            {text: 'Examples', link: '/markdown-examples'}
        ],

        sidebar: [
            {
                text: '快速入门',
                items: [
                    {text: '概述', link: '/markdown-examples'},
                    {text: '安装', link: '/api-examples'},
                    {text: '配置', link: '/api-examples'},
                    {text: '初始化', link: '/api-examples'},

                ]
            },

        ],

        socialLinks: [
            {icon: 'github', link: 'https://github.com/vuejs/vitepress'}
        ],

        search: {
            provider: 'local',
            options: {
                locales: {
                    zh: {
                        translations: {
                            button: {
                                buttonText: '搜索文档',
                                buttonAriaLabel: '搜索文档'
                            },
                            modal: {
                                noResultsText: '无法找到相关结果',
                                resetButtonTitle: '清除查询条件',
                                footer: {
                                    selectText: '选择',
                                    navigateText: '切换',
                                    closeText: '关闭'
                                }
                            }
                        }
                    }
                }
            }
        }
    },
    markdown: {
        config(md) {
            md.use(markdownItTextualUml);
        },
        image: {
            // image lazy loading is disabled by default
            lazyLoading: true
        }
    }
},vitePressSidebarOptions))
