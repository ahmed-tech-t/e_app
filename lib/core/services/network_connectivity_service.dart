import 'package:connectivity_plus/connectivity_plus.dart';

class NetworkConnectivityService {
  final Connectivity _connectivity;

  NetworkConnectivityService({Connectivity? connectivity})
    : _connectivity = connectivity ?? Connectivity();

  static final NetworkConnectivityService instance =
      NetworkConnectivityService();

  /// Stream emitting `true` when device is offline, and `false` when online.
  Stream<bool> get isOfflineStream {
    return _connectivity.onConnectivityChanged.map(_isOffline);
  }

  /// Initial check for current offline status.
  Future<bool> checkIsOffline() async {
    final results = await _connectivity.checkConnectivity();
    return _isOffline(results);
  }

  bool _isOffline(List<ConnectivityResult> results) {
    return results.isEmpty ||
        results.every((r) => r == ConnectivityResult.none);
  }
}
