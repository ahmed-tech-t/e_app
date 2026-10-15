class AppConfig {
  final String appName;
  final String baseUrl;
  // Global static instance accessible from anywhere in your app
  static late final AppConfig instance;
  AppConfig({required this.appName, required this.baseUrl});
}
